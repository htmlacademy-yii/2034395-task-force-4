<?php

namespace app\models;

use Yii;
use DateTime;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $status
 * @property string|null $email
 * @property string|null $username
 * @property string|null $password
 * @property int|null $city_id
 * @property int|null $is_executor
 * @property string|null $avatar_url
 * @property string|null $birthday
 * @property string|null $phone_number
 * @property string|null $telegram
 * @property string|null $details
 * @property string|null $registration_date
 * @property string|null $auth_key
 * @property string|null $access_token
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
 * @property int $age
 */
class User extends ActiveRecord implements IdentityInterface
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
            [['details', 'auth_key', 'access_token'], 'string'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [
                [
                    'username',
                    'telegram',
                    'password',
                    'avatar_url',
                    'status',
                    'phone_number'
                ],
                'string',
                'max' => 255
            ],
            [
                ['city_id'],
                'exist',
                'targetClass' => City::class,
                'targetAttribute' => ['city_id' => 'id']
            ]
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
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
        ];
    }

    public static function findIdentity($id): User|null
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): User|null
    {
        return self::findOne(['access_token' => $token]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
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
            $grades[] = (float)$review->grade;
        }

        if (count($grades) === 0) {
            return 0;
        }

        $result = array_sum($grades) / (float)count($grades);

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
            $grades[] = (float)$review->grade;
        }

        if (count($grades) === 0) {
            return 0;
        }

        $result = array_sum($grades) / (float)count($grades);

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

    /**
     * Gets int for [[Age]]
     *
     * @return int
     * @throws \Exception
     */
    public function getAge(): int
    {
        $now = new DateTime();
        $birthday = new DateTime($this->birthday);

        $interval = $now->diff($birthday);

        return (int)$interval->format('%Y');
    }
}
