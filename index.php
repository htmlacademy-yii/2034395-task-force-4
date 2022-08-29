<?php

use TaskForce\classes\entity\Task;
use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;

require_once "vendor/autoload.php";

$taskStatusNew = new Task(1, 1, 2);
$taskUserCustomer = new Task(1, 1, 2);
$taskUserPerformer = new Task(2, 1, 2);

$createAction = new CreateAction();
$cancelAction = new CancelAction();
$acceptAction = new AcceptAction();
$declineAction = new DeclineAction();
$endAction = new EndAction();

// Установка настроек тестирования
assert_options(ASSERT_ACTIVE, true);
assert_options(ASSERT_WARNING, false);
assert_options(ASSERT_BAIL, false);
assert_options(ASSERT_EXCEPTION,  false);
assert_options(ASSERT_CALLBACK, function($file, $line, $assertion, $message) {
    echo "$message <br>";
});

// Проверка каждого действия на следующий за ним статус задания
assert($taskStatusNew->getNextStatus($createAction) === Task::STATUS_NEW, 'Ошибка статуса "Новое задание" (STATUS_NEW)');
assert($taskStatusNew->getNextStatus($cancelAction) === Task::STATUS_CANCELED, 'Ошибка статуса "Задание отменено" (STATUS_CANCELED)');
assert($taskStatusNew->getNextStatus($acceptAction) === Task::STATUS_IN_WORK, 'Ошибка статуса "Задание отменено" (STATUS_IN_WORK)');
assert($taskStatusNew->getNextStatus($declineAction) === Task::STATUS_FAILED, 'Ошибка статуса "Задание отменено" (STATUS_FAILED)');
assert($taskStatusNew->getNextStatus($endAction) === Task::STATUS_PERFORMED, 'Ошибка статуса "Задание отменено" (STATUS_PERFORMED)');

// Проверка доступных (CREATE_ACTION) пользователю, являющемуся заказчиком, действий для нового (STATUS_NEW) задания
assert($taskUserCustomer->getAvailableActions() === [$createAction::class], 'Ошибка действия "Создать задание" (CreateAction)');

// Установка заданию, в котором пользователь является заказчиком, статуса "Новое задание" (STATUS_NEW)
$taskUserCustomer->status = Task::STATUS_NEW;
// Проверка доступных (CANCEL_ACTION) пользователю, являющемуся заказчиком, действий для нового (STATUS_NEW) задания
assert($taskUserCustomer->getAvailableActions() === [$cancelAction::class], 'Ошибка "Отменить задание" (CancelAction)');

// Установка заданию, в котором пользователь является исполнителем, статуса "Новое задание" (STATUS_NEW)
$taskUserPerformer->status = Task::STATUS_NEW;
// Проверка доступных (ACCEPT_ACTION) пользователю, являющемуся исполнителем, действий для нового (STATUS_NEW) задания
assert($taskUserPerformer->getAvailableActions() === [$acceptAction::class], 'Ошибка действия "Принять задание" (AcceptAction)');

// Установка заданию, в котором пользователь является исполнителем, статуса "На исполнении" (STATUS_IN_WORK)
$taskUserPerformer->status = Task::STATUS_IN_WORK;
// Проверка доступных (DECLINE_ACTION, END_ACTION) пользователю, являющемуся исполнителем, действий для принятого (STATUS_IN_WORK) задания
assert($taskUserPerformer->getAvailableActions() === [$declineAction::class, $endAction::class], 'Ошибка действия "Отказ от задания" (DeclineAction)');
