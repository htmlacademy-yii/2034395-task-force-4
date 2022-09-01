<?php

use TaskForce\classes\entity\Task;
use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;

require_once "vendor/autoload.php";

$task = null;

try {
    $task = new Task(Task::STATUS_UNDEFINED, 1, 1);
} catch (Exception $e) {
    echo $e->getMessage();
}

$createAction = new CreateAction();
$cancelAction = new CancelAction();
$acceptAction = new AcceptAction();
$declineAction = new DeclineAction();
$endAction = new EndAction();

// Проверка каждого действия на следующий за ним статус задания
try {
    echo $task->getNextStatus($createAction) . '<br>';
    echo $task->getNextStatus($cancelAction) . '<br>';
    echo $task->getNextStatus($acceptAction) . '<br>';
    echo $task->getNextStatus($declineAction) . '<br>';
    echo $task->getNextStatus($endAction) . '<br>';
} catch (Exception $e) {
    echo $e->getMessage();
}
