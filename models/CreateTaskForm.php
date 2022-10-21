<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Console;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class CreateTaskForm extends Model
{
    public ?string $title = null;
    public ?string $details = null;
    public ?int $category_id = null;
    public int $city_id = 1;
    public ?string $location = null;
    public int $budget = 0;
    public ?string $execution_date = null;
    public array $files = [];

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'details', 'category_id'], 'required'],
            ['title', 'string', 'min' => 10, 'max' => 255],
            ['details', 'string', 'min' => 30, 'max' => 255],
            ['location', 'string', 'max' => 255],
            [
                'execution_date',
                'datetime',
                'format' => 'Y-m-d H:i:s',
            ],
            [['category_id', 'city_id'], 'integer'],
            ['budget', 'integer', 'min' => 0],
            ['category_id', 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            ['city_id', 'exist', 'targetClass' => City::class, 'targetAttribute' => ['city_id' => 'id']],
            ['files', 'file', 'maxFiles' => 0]
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'title' => 'Суть работы',
            'details' => 'Подробности задания',
            'category_id' => 'Категория',
            'city_id' => 'Город',
            'location' => 'Локация',
            'budget' => 'Бюджет',
            'execution_date' => 'Срок выполнения',
            'files' => 'Файлы'
        ];
    }

    public function create(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $task = new Task();

        $task->title = $this->title;
        $task->details = $this->details;
        $task->category_id = $this->category_id;
        $task->city_id = $this->city_id;
        $task->location = $this->location;
        $task->budget = $this->budget;
        $task->execution_date = $this->execution_date;

        $task->status = Task::STATUS_NEW;
        $task->customer_id = Yii::$app->user->id;
        $task->creation_date = date('Y-m-d H:i:s', time());

        $task->save(false);

        return true;
    }
}