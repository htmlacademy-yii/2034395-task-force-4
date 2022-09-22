<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\City;

return [
    'email' => $faker->email,
    'username' => $faker->userName,
    'password' => Yii::$app->getSecurity()->generatePasswordHash($faker->password),
    'city_id' => $faker->randomElement(array_keys(City::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'is_executor' => $faker->boolean,
    'avatar_url' => $faker->imageUrl,
    'birthday' => date('Y.m.d H:i:s', $faker->dateTimeBetween->getTimestamp()),
    'phone_number' => substr($faker->e164PhoneNumber, 1, 11),
    'details' => $faker->text,
    'registration_date' => date('Y.m.d H:i:s', $faker->dateTimeThisYear->getTimestamp()),
];