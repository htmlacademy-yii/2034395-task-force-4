<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\Category;
use app\models\User;
use app\models\City;

return [
    'status' => 'new',
    'creation_date' => date('Y.m.d H:i:s', $faker->dateTimeThisYear->getTimestamp()),
    'title' => $faker->jobTitle,
    'details' => $faker->text,
    'category_id' => $faker->randomElement(array_keys(Category::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'customer_id' => $faker->randomElement(array_keys(User::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'executor_id' => $faker->randomElement(array_keys(User::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'city_id' => $faker->randomElement(array_keys(City::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'budget' => $faker->numberBetween(500, 999999),
    'execution_date' => date('Y.m.d H:i:s', $faker->dateTimeBetween('now', '++1 year')->getTimestamp())
];