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
assert_options(ASSERT_WARNING, true);
assert_options(ASSERT_BAIL, true);
assert($strategy->getNextStatus($createAction) == Task::STATUS_NEW, $exception = new Exception('Ошибка статуса'));
assert($strategy->getNextStatus($cancelAction) == Task::STATUS_NEW, $exception = new Exception('Ошибка статуса'));

$arr = $strategy->getAvailableActions();

foreach ($arr as $el) {
    if ($el) {
        echo($el . '<br>');
    }
}
