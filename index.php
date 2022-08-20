<?php
set_include_path('classes');
spl_autoload_register();

$strategy = new Task(0, 1);

if ($strategy->getNextStatus('cancel') == Task::STATUS_CANCELED) {
    echo('cancel action');
} else {
    echo('error');
}
