<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $username
 * @property string|null $password
 * @property string|null $city
 * @property int|null $is_executor
 * @property string|null $avatar_url
 * @property string|null $birthday
 * @property string|null $phone_number
 * @property string|null $telegram
 * @property string|null $details
 * @property string|null $registration_date
 *
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property Review[] $reviews0
 * @property Task[] $tasks
 * @property Task[] $tasks0
 * @property UserCategory[] $userCategories
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['is_executor'], 'integer'],
            [['birthday', 'registration_date'], 'safe'],
            [['details'], 'string'],
            [['email'], 'string', 'max' => 320],
            [['username', 'city', 'telegram'], 'string', 'max' => 128],
            [['password'], 'string', 'max' => 64],
            [['avatar_url'], 'string', 'max' => 2048],
            [['phone_number'], 'string', 'max' => 32],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Почта',
            'username' => 'Имя',
            'password' => 'Пароль',
            'city' => 'Город',
            'birthday' => 'Дата рождения',
            'phone_number' => 'Номер телефона',
            'telegram' => 'Telegram',
            'details' => 'Информация',
            'registration_date' => 'Дата регистрации',
        ];
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviewsByCustomerId(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return ActiveQuery
     */
    public function getReviewsByExecutorId(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return ActiveQuery
     */
    public function getTasksByCustomerId(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks0]].
     *
     * @return ActiveQuery
     */
    public function getTasksByExecutorId(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[UserCategories]].
     *
     * @return ActiveQuery
     */
    public function getUserCategories(): ActiveQuery
    {
        return $this->hasMany(UserCategory::class, ['user_id' => 'id']);
    }
}
