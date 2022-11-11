<?php

namespace app\models;

use GuzzleHttp\Exception\GuzzleException;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;
use app\helpers\GeocoderHelpers;
use app\validators\LocationValidator;
use app\validators\ExecutionDateValidator;

class CreateTaskForm extends Model
{
    public ?string $title = null;
    public ?string $details = null;
    public ?int $category_id = null;
    public ?string $location = null;
    public int|string|null $budget = 0;
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
            ['location', LocationValidator::class],
            ['execution_date', ExecutionDateValidator::class],
            ['category_id', 'integer'],
            ['budget', 'integer'],
            ['category_id', 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
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

    /**
     * Сохраняет файлы и создает запись в таблице для файлов заданий
     *
     * @throws Exception
     */
    public function saveFiles($taskId): bool
    {
        $files = UploadedFile::getInstances($this, 'files');

        foreach ($files as $file) {
            $newFile = new File();
            if (!$newFile->upload($file)) {
                return false;
            }

            $taskFile = new TaskFile();

            $taskFile->task_id = $taskId;
            $taskFile->file_id = $newFile->id;

            if (!$taskFile->save()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Создает карточку с заданием и записывает ее в базу данных
     *
     * @throws GuzzleException
     * @throws \Exception
     *
     * @return bool
     */
    public function create(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $geocoder = GeocoderHelpers::getGeocoderData($this->location);

        $address = $geocoder?->description;

        $coords = explode(' ', $geocoder?->Point->pos);

        $city = City::findOne(['name' => explode(',', $address)[0] ?? null]);

        $task = new Task();

        $task->title = $this->title;
        $task->details = $this->details;
        $task->category_id = $this->category_id;

        $task->budget = $this->budget ?? 0;

        if ($this->execution_date) {
            $task->execution_date = date('Y-m-d H:i:s', strtotime($this->execution_date));
        }

        $task->status = Task::STATUS_NEW;
        $task->customer_id = Yii::$app->user->id;
        $task->creation_date = date('Y-m-d H:i:s', time());

        $task->location = $geocoder?->name;
        $task->location_lat = $coords[1] ?? null;
        $task->location_long = $coords[0] ?? null;

        if ($city?->id) {
            $task->city_id = $city->id;
        }

        $task->save(false);

        $this->saveFiles($task->id);

        return true;
    }
}