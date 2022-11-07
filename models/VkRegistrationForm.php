<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\StaleObjectException;

class VkRegistrationForm extends Model
{
    public ?string $username = null;
    public ?string $email = null;
    public ?int $city_id = null;
    public ?string $password = null;
    public ?string $birthday = null;
    public bool $is_executor = true;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'city_id', 'password', 'is_executor'], 'required'],
            [['username', 'password'], 'string', 'max' => 255],
            ['email', 'email'],
            ['birthday', 'safe'],
            ['email', 'unique', 'targetClass' => User::class, 'targetAttribute' => 'email'],
            ['is_executor', 'boolean'],
            ['city_id', 'exist', 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
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
            'is_executor' => 'Is Executor',
        ];
    }

    /**
     * Создает аккаунт нового пользователя, обрабатывая данные, пришедшие в POST запросе
     *
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return bool
     */
    public function register(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();

        $user->username = $this->username;
        $user->email = $this->email;
        $user->birthday = $this->birthday;
        $user->status = User::STATUS_FREE;
        $user->city_id = $this->city_id;
        $user->is_executor = (int)$this->is_executor;
        $user->registration_date = date('Y-m-d H:i:s', time());

        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->access_token = Yii::$app->security->generateRandomString();

        if ($user->save(false)) {
            return Yii::$app->user->login($user);
        }

        return false;
    }
}