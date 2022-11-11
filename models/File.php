<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $type
 * @property int|null $size
 *
 * @property TaskFile[] $taskFiles
 */
class File extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['url'], 'string', 'max' => 2048],
            [['type'], 'string', 'max' => 16],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'url' => 'Ссылка',
            'type' => 'Тип',
        ];
    }

    /**
     * Gets query for [[TaskFiles]].
     *
     * @return ActiveQuery
     */
    public function getTaskFiles(): ActiveQuery
    {
        return $this->hasMany(TaskFile::class, ['file_id' => 'id']);
    }

    /**
     * Загружает файл в базу данных
     *
     * @param UploadedFile $file
     *
     * @throws Exception
     *
     * @return bool
     */
    public function upload(UploadedFile $file): bool
    {
        if (!file_exists("@webroot/uploads")) {
            FileHelper::createDirectory("uploads");
        }

        $extension = $file->getExtension();

        $name = uniqId('upload') . ".$extension";

        $file->saveAs("@webroot/uploads/$name");

        $this->url = "/uploads/$name";
        $this->type = $extension;
        $this->size = $file->size;

        return $this->save();
    }
}
