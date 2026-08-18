<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getLinks()
    {
        return $this->hasMany(Link::class, ['user_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            $this->updated_at = time();
            
            // Хешируем пароль только если он изменился или это новая запись
            if ($this->isAttributeChanged('password')) {
                $this->password = \Yii::$app->security->generatePasswordHash($this->password);
            }
            
            return true;
        }
        return false;
    }
}
