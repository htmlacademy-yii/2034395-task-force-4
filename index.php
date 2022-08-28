<?php

use TaskForce\classes\entity\Task;
use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;

require_once "vendor/autoload.php";

$strategy = new Task(1, 1, 2);

$createAction = new CreateAction();
$cancelAction = new CancelAction();
$acceptAction = new AcceptAction();
$declineAction = new DeclineAction();
$endAction = new EndAction();

assert_options(ASSERT_ACTIVE, true);
assert_options(ASSERT_WARNING, false);
assert_options(ASSERT_BAIL, false);
assert_options(ASSERT_EXCEPTION,  false);
assert_options(ASSERT_CALLBACK, function($file, $line, $assertion, $message) {
    echo "$message <br>";
});

assert($strategy->getNextStatus($createAction) === Task::STATUS_NEW, 'Ошибка статуса');
assert($strategy->getNextStatus($cancelAction) === Task::STATUS_NEW, 'Ошибка статуса');
assert($strategy->getAvailableActions() === [$cancelAction::class], 'Ошибка: нет прав на выполнение действия "Отменить задание"');
assert($strategy->getAvailableActions() === [$endAction::class], 'Ошибка: нет прав на выполнение действия "Завершить задание"');
assert($strategy->getAvailableActions() === [$acceptAction::class], 'Ошибка: нет прав на выполнение действия "Принять задание"');
assert($strategy->getAvailableActions() === [$declineAction::class], 'Ошибка: нет прав на выполнение действия "Отклонить задание"');
assert($strategy->getAvailableActions() === [$createAction::class], 'Ошибка: нет прав на выполнение действия "Создать задание"');

echo 'Done! <br>';
