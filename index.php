<?php

use TaskForce\classes\entity\Task;
use TaskForce\classes\actions\CreateAction;
use TaskForce\classes\actions\CancelAction;
use TaskForce\classes\actions\AcceptAction;
use TaskForce\classes\actions\DeclineAction;
use TaskForce\classes\actions\EndAction;

require_once "vendor/autoload.php";

$strategy = new Task(1, 1, 2);

$create_action = new CreateAction();

if ($strategy->getNextStatus($create_action) == Task::STATUS_NEW) {
    echo('create action <br>');
} else {
    echo('error <br>');
}

$arr = $strategy->getAvailableActions($create_action);

foreach ($arr as $el) {
    if ($el) {
        echo($el . '<br>');
    }
}
