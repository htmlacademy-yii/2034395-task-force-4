<?php

namespace app\models;

use Yii;
use yii\base\Model;

class EndTaskForm extends Model
{
    public ?int $task_id = null;
    public ?int $customer_id = null;
    public ?int $executor_id = null;
    public ?string $text = null;
    public ?string $grade = null;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'customer_id', 'executor_id', 'text', 'grade'], 'required'],
            ['text', 'string', 'min' => 10, 'max' => 255],
            [['task_id', 'customer_id', 'executor_id', 'grade'], 'integer'],
            ['task_id', 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            ['customer_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['customer_id' => 'id']],
            ['executor_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['executor_id' => 'id']],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'task_id' => 'Задание',
            'customer_id' => 'Заказчик',
            'executor_id' => 'Исполнитель',
            'text' => 'Комментарий',
            'grade' => 'Оценка'
        ];
    }

    public function end(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $review = new Review();

        $review->task_id = $this->task_id;
        $review->customer_id = $this->customer_id;
        $review->executor_id = $this->executor_id;
        $review->text = $this->text;
        $review->grade = (int) $this->grade;
        $review->creation_date = date('Y-m-d H:i:s', time());

        $review->save(false);

        $task = Task::findOne($this->task_id);

        $task->status = Task::STATUS_PERFORMED;

        return $task->update();
    }
}