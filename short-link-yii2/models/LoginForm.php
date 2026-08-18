<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm model
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверные учетные данные.');
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by email
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }
}
