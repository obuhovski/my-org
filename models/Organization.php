<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property integer $id
 * @property string $displayName
 * @property integer $ogrn
 * @property integer $oktmo
 *
 * @property User[] $users
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['displayName'], 'required'],
            [['ogrn', 'oktmo'], 'integer'],
            [['displayName'], 'string', 'max' => 255],
            [['ogrn'], 'unique'],
            [['oktmo'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'displayName' => 'Наименование',
            'ogrn' => 'ОРГН',
            'oktmo' => 'ОКТМО',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['org_id' => 'id']);
    }
}
