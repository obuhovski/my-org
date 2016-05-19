<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $middlename
 * @property string $b_date
 * @property integer $inn
 * @property integer $snils
 * @property integer $org_id
 *
 * @property Organization $org
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['b_date'], 'date', 'format' => 'yyyy-mm-dd'],
            [['firstname', 'lastname', 'inn', 'snils', 'org_id'], 'required'],
            [['inn', 'snils', 'org_id'], 'integer'],
            [['firstname', 'lastname', 'middlename'], 'string', 'max' => 255],
            [['inn'], 'unique'],
            ['inn', 'string', 'length' => 16],
            [['snils'], 'unique'],
            ['snils', 'string', 'length' => 13],
            [['org_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['org_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'firstname' => 'Имя',
            'lastname' => 'Фамилия',
            'middlename' => 'Отчество',
            'b_date' => 'Двта рождения',
            'inn' => 'ИНН',
            'snils' => 'СНИЛС',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrg()
    {
        return $this->hasOne(Organization::className(), ['id' => 'org_id']);
    }
}
