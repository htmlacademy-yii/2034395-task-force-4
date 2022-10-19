<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public ?string $email = null;
    public ?string $password = null;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
        ];
    }

    public function validatePassword(): bool
    {
        $user = User::findOne(['email' => $this->email]);

        if ($user->validatePassword($this->password)) {
            return true;
        }

        $this->addError('password', 'Неверный пароль.');
        return false;
    }

    public function login(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = User::findOne(['email' => $this->email]);
        return Yii::$app->user->login($user);
    }
}