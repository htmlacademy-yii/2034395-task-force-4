<?php

namespace app\models;

use app\helpers\MainHelpers;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class CreateTaskForm extends Model
{
    public ?string $title = null;
    public ?string $details = null;
    public ?int $category_id = null;
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
            ['location', 'validateLocation'],
            ['execution_date', 'safe',],
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

    public function validateLocation(): bool
    {
        $geocoder = MainHelpers::getGeocoderData($this->location);

        $city = City::findOne(['name' => explode(',', $geocoder?->description)[0] ?? null]);

        if (!$city) {
            $this->addError('location', 'Город не найден');
            return false;
        }

        return true;
    }

    public function create(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $geocoder = MainHelpers::getGeocoderData($this->location);

        $address = $geocoder?->description;

        $coords = explode(' ', $geocoder?->Point->pos);

        $city = City::findOne(['name' => explode(',', $address)[0] ?? null]);

        $task = new Task();

        $task->title = $this->title;
        $task->details = $this->details;
        $task->category_id = $this->category_id;

        $task->budget = $this->budget;

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

        foreach (UploadedFile::getInstances($this, 'files') as $file) {
            $newFile = new File();
            $extension = $file->getExtension();

            $name = uniqId('upload') . ".$extension";

            $file->saveAs("@webroot/uploads/$name");

            $newFile->url = "/uploads/$name";
            $newFile->type = $extension;
            $newFile->size = $file->size;

            $newFile->save();

            $taskFile = new TaskFile();

            $taskFile->task_id = $task->id;
            $taskFile->file_id = $newFile->id;

            $taskFile->save();
        }

        return true;
    }
}