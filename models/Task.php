<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string|null $status
 * @property string|null $creation_date
 * @property string|null $title
 * @property string|null $details
 * @property int|null $category_id
 * @property int|null $customer_id
 * @property int|null $executor_id
 * @property int|null $city_id
 * @property string|null $location
 * @property float|null $location_lat
 * @property float|null $location_long
 * @property int|null $budget
 * @property string|null $execution_date
 *
 * @property Category $category
 * @property City $city
 * @property User $customer
 * @property User $executor
 * @property Response[] $responses
 * @property Review[] $reviews
 * @property TaskFile[] $taskFiles
 * @property string $statusLabel
 */
class Task extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELED = 'canceled';
    const STATUS_IN_WORK = 'in work';
    const STATUS_PERFORMED = 'performed';
    const STATUS_FAILED = 'failed';

    const STATUS_MAP = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_IN_WORK => 'В работе',
        self::STATUS_PERFORMED => 'Выполнено',
        self::STATUS_FAILED => 'Провалено',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status', 'title', 'details'], 'string'],
            [['creation_date', 'execution_date'], 'safe'],
            [['category_id', 'customer_id', 'executor_id', 'city_id', 'budget'], 'integer'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'Статус',
            'status' => 'Статус',
            'creation_date' => 'Дата создания',
            'title' => 'Название',
            'details' => 'Описание',
            'category_id' => 'Категория',
            'customer_id' => 'Заказчик',
            'executor_id' => 'Исполнитель',
            'city_id' => 'Город',
            'location' => 'Улица',
            'budget' => 'Бюджет',
            'execution_date' => 'Дата сдачи',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
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
     * Gets query for [[Customer]].
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Gets query for [[Responses]].
     *
     * @return ActiveQuery
     */
    public function getResponses(): ActiveQuery
    {
        return $this->hasMany(Response::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return ActiveQuery
     */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return ActiveQuery
     */
    public function getTaskFiles(): ActiveQuery
    {
        return $this->hasMany(TaskFile::class, ['task_id' => 'id']);
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
     * Проверяет, является ли пользователь заказчиком и статус задания для того, чтобы изменить его статус на "Выполнено"
     *
     * @return bool
     */
    public function end(): bool
    {
        if ($this->customer_id !== Yii::$app->user->id || $this->status !== self::STATUS_IN_WORK) {
            return false;
        }

        $model = new EndTaskForm();

        if ($model->load(Yii::$app->request->post()) && $model->end()) {
            return true;
        }

        return false;
    }

    /**
     * Проверяет, является ли пользователь исполнителем и статус задания для того, чтобы изменить его статус на "Провалено"
     *
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return bool
     */
    public function decline(): bool
    {
        if ($this->executor_id !== Yii::$app->user->id || $this->status !== self::STATUS_IN_WORK) {
            return false;
        }

        $this->status = self::STATUS_FAILED;
        return $this->update();
    }

    /**
     * Проверяет, является ли пользователь заказчиком и статус задания для того, чтобы изменить его статус на "Отменено"
     *
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return bool
     */
    public function cancel(): bool
    {
        if ($this->customer_id !== Yii::$app->user->id || $this->status !== self::STATUS_NEW) {
            return false;
        }

        $this->status = self::STATUS_CANCELED;

        $this->update(false);

        foreach ($this->responses as $response) {
            $response->status = Response::STATUS_DECLINED;
            $response->update();
        }

        return true;
    }
}
