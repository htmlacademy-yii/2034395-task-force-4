<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use app\models\User;
use app\models\Task;

return [
    'creation_date' => date('Y.m.d H:i:s', $faker->dateTimeThisYear->getTimestamp()),
    'text' => $faker->text,
    'executor_id' => $faker->randomElement(array_keys(User::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
    'task_id' => $faker->randomElement(array_keys(Task::find()->select(['id'])->asArray()->indexBy(['id'])->all())),
];