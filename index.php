<?php
require_once "vendor/autoload.php";

$strategy = new Task(0, 1);

if ($strategy->getNextStatus('cancel') == Task::STATUS_CANCELED) {
    echo('cancel action');
} else {
    echo('error');
}
