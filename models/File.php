<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string|null $url
 * @property string|null $type
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
}
