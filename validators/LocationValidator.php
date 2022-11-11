<?php

namespace app\validators;

use GuzzleHttp\Exception\GuzzleException;
use yii\validators\Validator;
use app\helpers\GeocoderHelpers;
use app\models\City;
use yii\base\Model;

class LocationValidator extends Validator
{
    /**
     * Проверяет, существует ли указанный город в базе данных
     *
     * @param Model $model
     * @param string $attribute
     *
     * @throws GuzzleException
     */
    public function validateAttribute($model, $attribute): void
    {
        $geocoder = GeocoderHelpers::getGeocoderData($model->location);

        $city = City::findOne(['name' => explode(',', $geocoder?->description)[0] ?? null]);

        if (!$city) {
            $this->addError($model, $attribute, 'Город не найден');
        }
    }
}