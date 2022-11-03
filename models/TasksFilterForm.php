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
     * Возвращает массив заданий, фильтруя их, исходя из заданных параметров
     *
     * @return array
     */
    public function filter(): array
    {
        $tasks = Task::find()->where(['status' => Task::STATUS_NEW]);

        if (!empty($this->categoryIds)) {
            $tasks = $tasks->andWhere(['category_id' => $this->categoryIds]);
        }

        if (!empty($this->additional)) {
            foreach ($this->additional as $condition) {
                $tasks = $tasks->andWhere($condition);
            }
        }

        if (strlen($this->period) > 0) {
            $timestamp = strtotime($this->period);
            $datetime = date("Y-m-d H:i:s", $timestamp);

            $tasks->andWhere(['>', 'creation_date', $datetime]);
        }

        return $tasks->limit(5)
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }
}