<?php

use TaskForce\classes\entity\Task;

require_once "vendor/autoload.php";

$strategy = new Task(1, 1, 2);

if ($strategy->getNextStatus('cancel') == Task::STATUS_CANCELED) {
    echo('cancel action');
} else {
    echo('error');
}
