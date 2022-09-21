<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $lat
 * @property float|null $long
 */
class City extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['lat', 'long'], 'number'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
            'lat' => 'Широта',
            'long' => 'Долгота',
        ];
    }
}
