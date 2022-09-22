<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\User;
use app\models\Task;

return [
    'customer_id' => $faker->randomElement(array_keys(User::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'executor_id' => $faker->randomElement(array_keys(User::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'task_id' => $faker->randomElement(array_keys(Task::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'grade' => $faker->numberBetween(0, 5),
    'text' => $faker->text,
];