<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "response".
 *
 * @property int $id
 * @property string $status
 * @property string|null $creation_date
 * @property string|null $text
 * @property int|null $price
 * @property int|null $executor_id
 * @property int|null $task_id
 *
 * @property User $executor
 * @property Task $task
 * @property string $statusLabel
 */
class Response extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    const STATUS_MAP = [
        self::STATUS_NEW => 'Новый отклик',
        self::STATUS_ACCEPTED => 'Отклик принят',
        self::STATUS_DECLINED => 'Отклик отклонен'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'response';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['creation_date'], 'safe'],
            [['text'], 'string'],
            [['price', 'executor_id', 'task_id'], 'integer'],
            [
                ['executor_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' => ['executor_id' => 'id']
            ],
            [
                ['task_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'creation_date' => 'Дата создания',
            'text' => 'Текст отклика',
            'executor_id' => 'Исполнитель',
            'task_id' => 'Задание',
            'price' => 'Цена',
        ];
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
     * Gets query for [[Task]].
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
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
     * Создает новый отклик
     *
     * @return bool
     */
    public function create(): bool
    {
        $model = new CreateResponseForm();

        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            return true;
        }

        return false;
    }

    /**
     * Проверяет, является ли пользователь заказчиком и меняет статус отклика на "Отклонен"
     *
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return bool
     */
    public function cancel(): bool
    {
        if ($this->task->customer_id !== Yii::$app->user->id) {
            return false;
        }

        $this->status = self::STATUS_DECLINED;
        return $this->update();
    }

    /**
     * Проверяет, является ли пользователь заказчиком и меняет статус отклика на "Принят", также меняет статус задания на "В работе"
     *
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return bool
     */
    public function submit(): bool
    {
        if ($this->task->customer_id !== Yii::$app->user->id) {
            return false;
        }

        $this->task->executor_id = $this->executor_id;
        $this->task->status = Task::STATUS_IN_WORK;

        if (!$this->task->update()) {
            return false;
        }

        foreach ($this->task->responses as $response) {
            $response->status = match ($response->id) {
                $this->id => self::STATUS_ACCEPTED,
                default => self::STATUS_DECLINED
            };

            $response->update();
        }

        return true;
    }
}
