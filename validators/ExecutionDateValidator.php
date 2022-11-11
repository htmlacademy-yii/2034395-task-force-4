<?php

namespace app\validators;

use yii\base\Model;
use yii\validators\Validator;

class ExecutionDateValidator extends Validator
{
    /**
     * Проверяет, меньше ли текущей даты дата исполнения заказа
     *
     * @param Model $model
     * @param string $attribute
     *
     * @return void
     */
    public function validateAttribute($model, $attribute): void
    {
        if (strtotime($model->execution_date) < time()) {
            $model->addError('execution_date', 'Срок исполнения не может быть раньше сегодняшней даты');
        }
    }

}