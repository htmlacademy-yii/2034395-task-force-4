<?php

use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;
use TaskForce\classes\entity\Task;
use TaskForce\classes\exceptions\WrongFilenameOrPathException;
use TaskForce\classes\exceptions\WrongStatusException;
use TaskForce\classes\imports\ImportCSV;

require_once "vendor/autoload.php";

$taskStatusNew = null;
$taskUserCustomer = null;
$taskUserPerformer = null;

try {
    $taskStatusNew = new Task(Task::STATUS_NEW, 1, 1, 2);
    $taskUserCustomer = new Task(Task::STATUS_UNDEFINED, 1, 1, 2);
    $taskUserPerformer = new Task(Task::STATUS_NEW, 2, 1, 2);

} catch (Exception $e) {
    echo $e->getMessage();
}

try {
    $task = new Task("test", 1, 1);
} catch (Exception $e) {
    assert(get_class($e) === WrongStatusException::class, "Status Exception not equal WrongStatusException::class");
}

$createAction = new CreateAction();
$cancelAction = new CancelAction();
$acceptAction = new AcceptAction();
$declineAction = new DeclineAction();
$endAction = new EndAction();

// Установка настроек тестирования
assert_options(ASSERT_ACTIVE, true);
assert_options(ASSERT_WARNING, false);
assert_options(ASSERT_BAIL, false);
assert_options(ASSERT_EXCEPTION, false);
assert_options(ASSERT_CALLBACK, function ($file, $line, $assertion, $message) {
    echo "$message <br>";
});

// Проверка каждого действия на следующий за ним статус задания
assert($taskStatusNew->getNextStatus($cancelAction) === Task::STATUS_CANCELED,
    'Ошибка статуса "Задание отменено" (STATUS_CANCELED)');
assert($taskStatusNew->getNextStatus($acceptAction) === Task::STATUS_IN_WORK,
    'Ошибка статуса "Задание отменено" (STATUS_IN_WORK)');
assert($taskStatusNew->getNextStatus($declineAction) === Task::STATUS_FAILED,
    'Ошибка статуса "Задание отменено" (STATUS_FAILED)');
assert($taskStatusNew->getNextStatus($endAction) === Task::STATUS_PERFORMED,
    'Ошибка статуса "Задание отменено" (STATUS_PERFORMED)');

// Проверка доступных (CREATE_ACTION) пользователю, являющемуся заказчиком, действий для нового (STATUS_NEW) задания
assert($taskUserCustomer->getAvailableActions() === [$createAction::class],
    'Ошибка действия "Создать задание" (CreateAction)');

// Установка заданию, в котором пользователь является заказчиком, статуса "Новое задание" (STATUS_NEW)
$taskUserCustomer->status = Task::STATUS_NEW;
// Проверка доступных (CANCEL_ACTION) пользователю, являющемуся заказчиком, действий для нового (STATUS_NEW) задания
assert($taskUserCustomer->getAvailableActions() === [$cancelAction::class], 'Ошибка "Отменить задание" (CancelAction)');

// Установка заданию, в котором пользователь является исполнителем, статуса "Новое задание" (STATUS_NEW)
$taskUserPerformer->status = Task::STATUS_NEW;
// Проверка доступных (ACCEPT_ACTION) пользователю, являющемуся исполнителем, действий для нового (STATUS_NEW) задания
assert($taskUserPerformer->getAvailableActions() === [$acceptAction::class],
    'Ошибка действия "Принять задание" (AcceptAction)');

// Установка заданию, в котором пользователь является исполнителем, статуса "На исполнении" (STATUS_IN_WORK)
$taskUserPerformer->status = Task::STATUS_IN_WORK;
// Проверка доступных (DECLINE_ACTION, END_ACTION) пользователю, являющемуся исполнителем, действий для принятого (STATUS_IN_WORK) задания
assert($taskUserPerformer->getAvailableActions() === [$declineAction::class, $endAction::class],
    'Ошибка действия "Отказ от задания" (DeclineAction)');

// Проверка каждого действия на следующий за ним статус задания
try {
    echo $taskStatusNew->getNextStatus($createAction) . '<br>';
    echo $taskStatusNew->getNextStatus($cancelAction) . '<br>';
    echo $taskStatusNew->getNextStatus($acceptAction) . '<br>';
    echo $taskStatusNew->getNextStatus($declineAction) . '<br>';
    echo $taskStatusNew->getNextStatus($endAction) . '<br>';
} catch (Exception $e) {
    echo $e->getMessage();
}

$categories = new ImportCSV('src/data/categories.csv');
try {
    $categories->prepare();
    $categories->convertToSql('src/data/queryCategories.sql', 'categories',  ['name', 'icon']);
} catch (WrongFilenameOrPathException $e) {
    echo $e->getMessage();
}

$cities = new ImportCSV('src/data/cities.csv');
try {
    $cities->prepare();
    $cities->convertToSql('src/data/queryCities.sql', 'cities', ['name', 'lat', "`long`"]);
} catch (WrongFilenameOrPathException $e) {
    echo $e->getMessage();
}
