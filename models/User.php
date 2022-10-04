<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $status
 * @property string|null $email
 * @property string|null $username
 * @property int|null $age
 * @property string|null $password
 * @property int|null $city_id
 * @property int|null $is_executor
 * @property string|null $avatar_url
 * @property string|null $birthday
 * @property string|null $phone_number
 * @property string|null $telegram
 * @property string|null $details
 * @property string|null $registration_date
 *
 * @property City $city
 * @property Response[] $responses
 * @property Review[] $customerReviews
 * @property Review[] $executorReviews
 * @property Task[] $customerTasks
 * @property Task[] $executorTasks
 * @property UserCategory[] $userCategories
 * @property float $executorRating
 * @property float $customerRating
 * @property string $statusLabel
 * @property Task[] $performedTasks
 * @property Task[] $failedTasks
 */
class User extends ActiveRecord
{
    const STATUS_FREE = 'free';
    const STATUS_BUSY = 'busy';

    const STATUS_MAP = [
        self::STATUS_FREE => 'Открыт для новых заказов',
        self::STATUS_BUSY => 'Занят'
    ];

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
            [['city_id', 'is_executor', 'age'], 'integer'],
            [['birthday', 'registration_date'], 'safe'],
            [['details'], 'string'],
            [['email'], 'string', 'max' => 320],
            [['username', 'telegram'], 'string', 'max' => 128],
            [['password'], 'string', 'max' => 64],
            [['avatar_url'], 'string', 'max' => 2048],
            [['status, phone_number'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'email' => 'Почта',
            'username' => 'Имя пользователя',
            'age' => 'Возраст',
            'password' => 'Пароль',
            'city_id' => 'Город',
            'is_executor' => 'Is Executor',
            'avatar_url' => 'Avatar URL',
            'birthday' => 'Дата рождения',
            'phone_number' => 'Номер телефона',
            'telegram' => 'Telegram',
            'details' => 'Описание',
            'registration_date' => 'Дата регистрации',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return ActiveQuery
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
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
     * Gets query for [[CustomerReviews]].
     *
     * @return ActiveQuery
     */
    public function getCustomerReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[ExecutorReviews]].
     *
     * @return ActiveQuery
     */
    public function getExecutorReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id']);
    }

    /**
     * Gets query for [[customerTasks]].
     *
     * @return ActiveQuery
     */
    public function getCustomerTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['customer_id' => 'id']);
    }

    /**
     * Gets query for [[executorTasks]].
     *
     * @return ActiveQuery
     */
    public function getExecutorTasks(): ActiveQuery
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

    /**
     * Gets user rating by reviews grade for [[ExecutorRating]]
     *
     * @return float
     */
    public function getExecutorRating(): float
    {
        $grades = [];

        foreach ($this->executorReviews as $review) {
            $grades[] = (float) $review->grade;
        }

        if (count($grades) === 0) {
            return 0;
        }

        $result = array_sum($grades) / (float) count($grades);

        return round($result, 2);
    }

    /**
     * Gets user rating by reviews grade for [[ExecutorRating]]
     *
     * @return float
     */
    public function getCustomerRating(): float
    {
        $grades = [];

        foreach ($this->customerReviews as $review) {
            $grades[] = (float) $review->grade;
        }

        if (count($grades) === 0) {
            return 0;
        }

        $result = array_sum($grades) / (float) count($grades);

        return round($result, 2);
    }

    /**
     * Gets string for [[StatusLabel]].
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return self::STATUS_MAP[$this->status];
    }

    /**
     * Gets array for [[PerformedTasks]]
     *
     * @return array
     */
    public function getPerformedTasks(): array
    {
        return Task::findAll(['executor_id' => $this->id, 'status' => Task::STATUS_PERFORMED]);
    }

    /**
     * Gets array for [[FailedTasks]]
     *
     * @return array
     */
    public function getFailedTasks(): array
    {
        return Task::findAll(['executor_id' => $this->id, 'status' => Task::STATUS_FAILED]);
    }
}
