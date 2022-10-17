<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    public ?string $username = null;
    public ?string $email = null;
    public ?int $city_id = null;
    public ?string $password = null;
    public ?string $password_repeat = null;
    public bool $is_executor = true;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'city_id', 'password', 'password_repeat', 'is_executor'], 'required'],
            [['username', 'password', 'password_repeat'], 'string', 'max' => 255],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['is_executor', 'boolean'],
            ['city_id', 'exist', 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Почта',
            'city_id' => 'Город',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
            'is_executor' => 'Is Executor',
        ];
    }

    public function register(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->city_id = $this->city_id;
        $user->is_executor = (int)$this->is_executor;
        $user->password = Yii::$app->security->generatePasswordHash($this->password);
        $user->registration_date = date('Y-m-d H:i:s', time());

        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->access_token = Yii::$app->security->generateRandomString();

        if ($user->save(false)) {
            return Yii::$app->user->login($user);
        }

        return false;
    }
}