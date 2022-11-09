<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\StaleObjectException;

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
        $user->status = User::STATUS_FREE;
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

    public function vkRegister(array $data): bool
    {
        $city = City::findOne(['name' => $userData['city']['title'] ?? null]);

        $user = new User();

        $user->vk_id = $data['id'] ?? null;
        $user->username = $data['first_name'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->status = User::STATUS_FREE;
        $user->details = $data['about'] ?? null;
        $user->avatar_url = $data['photo_200_orig'];
        $user->is_executor = 1;

        if ($city) {
            $user->city_id = $city->id;
        }

        $user->birthday = $data['bdate'] ?? null;
        $user->registration_date = date('Y-m-d H:i:s', time());

        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->access_token = Yii::$app->security->generateRandomString();

        if ($user->save(false)) {
            return Yii::$app->user->login($user);
        }

        return false;
    }
}