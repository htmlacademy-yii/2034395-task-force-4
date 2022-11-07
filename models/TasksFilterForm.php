<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveQuery;

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
            ['period', 'string'],
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

    /**
     * Возвращает отфильтрованный запрос для отображения заданий
     *
     * @return ActiveQuery
     */
    public function filter(): ActiveQuery
    {
        $query = Task::find();
        $query->andFilterWhere(['status' => Task::STATUS_NEW]);

        if (!empty($this->categoryIds)) {
            $query->andFilterWhere(['category_id' => $this->categoryIds]);
        }

        if (!empty($this->additional)) {
            foreach ($this->additional as $condition) {
                $query->andFilterWhere($condition);
            }
        }

        if (strlen($this->period) > 0) {
            $timestamp = strtotime($this->period);
            $datetime = date("Y-m-d H:i:s", $timestamp);

            $query->andFilterWhere(['>', 'creation_date', $datetime]);
        }

        return $query;
    }
}