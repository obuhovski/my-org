<?php

namespace app\models\forms;

use app\helpers\XmlHelper;
use app\models\Organization;
use app\models\User;
use ErrorException;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ImportDataOrgForm extends Model
{
    public $file;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['file', 'required', 'message' => 'Файл не выбран'],
            ['file', 'file', 'skipOnEmpty' => false],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'file' => 'Выберите файл',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $uploadsDir = Yii::getAlias('@app').'/uploads';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777 , true);
            }
            $filePath = $uploadsDir . '/'  . uniqid() . '.' . $this->file->extension;
            $this->file->saveAs($filePath);
            return $filePath;
        } else {
            return null;
        }
    }
    
    public function parseXmlFileAndSaveData($filePath)
    {
        $succesAdded = 0;

        try {
            $orgsData = XmlHelper::xmlFIleToArray($filePath);
            if (isset($orgsData['org']) && is_array($orgsData['org'])) {
                foreach ($orgsData['org'] as $orgData) {
                    if (isset($orgData['@attributes'])) {
                        $org = new Organization($orgData['@attributes']);
                        if ($org->save()) {
                            $succesAdded++;
                            if (isset($orgData['user']) && is_array($orgData['user'])) {
                                foreach ($orgData['user'] as $userData) {
                                    $user = new User(
                                        isset($userData['@attributes']) ? $userData['@attributes'] : $userData
                                    );
                                    $user->org_id = $org->id;

                                    if (!$user->save()) {
                                        Yii::warning('Не удалось записать пользователя с данным: ' . var_export($orgData['@attributes']));
                                    }
                                }
                            }

                        } else {
                            Yii::warning('Не удалось записать ораганизацию с данными: ' . var_export($orgData['@attributes']));
                        }
                    }
                }
            }

        } catch (ErrorException $e) {
            Yii::error('Ошибка парсинга xml-файла: '. $e->getMessage());
            return null;
        }

        return $succesAdded;
    }
}
