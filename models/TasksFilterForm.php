<?php

namespace app\models;

use yii\base\Model;

class TasksFilterForm extends Model
{
    public array|string $categoryIds = '';
    public array|string $additional = '';
    public int|string $period = '';

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['categoryIds', 'additional'], 'safe'],
            ['period', 'integer'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return [
            'categoryIds' => 'Категории',
            'additional' => 'Дополнительные параметры',
            'period' => 'Период'
        ];
    }
}