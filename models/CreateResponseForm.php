<?php

namespace app\models;

use Yii;
use yii\base\Model;

class CreateResponseForm extends Model
{
    public ?int $task_id = null;
    public ?int $price = null;
    public ?string $text = null;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['task_id', 'price', 'text'], 'required'],
            ['text', 'string', 'min' => 10, 'max' => 255],
            ['price', 'integer'],
            ['task_id', 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'task_id' => 'Задание',
            'price' => 'Стоимость',
            'text' => 'Комментарий'
        ];
    }

    public function create(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $user = Yii::$app->user->identity;
        $task = Task::findOne($this->task_id);

        if ($task->executor_id || $user->getIsUserAcceptedTask($task->id)) {
            $this->addError('text', 'На это задание невозможно откликнуться.');
            return false;
        }

        $response = new Response();

        $response->executor_id = Yii::$app->user->id;
        $response->status = Response::STATUS_NEW;
        $response->task_id = $this->task_id;
        $response->price = $this->price;
        $response->text = $this->text;
        $response->creation_date = date('Y-m-d H:i:s', time());

        $response->save(false);

        return true;
    }
}