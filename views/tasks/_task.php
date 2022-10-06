<?php

use app\models\Task;
use app\helpers\MainHelpers;
use yii\helpers\Html;

/**
 * @var Task $task
 */

?>

<div class="task-card">
    <div class="header-task">
        <?= Html::a(Html::encode($task->title), ['tasks/view', 'id' => $task->id], ['class' => 'link link--block link--big']) ?>
        <p class="price price--task"><?= Html::encode($task->budget) ?> ₽</p>
    </div>
    <p class="info-text">
        <span class="current-time"><?= MainHelpers::normalizeDate($task->creation_date) ?> </span>назад
    </p>
    <p class="task-text">
        <?= Html::encode($task->details) ?>
    </p>
    <div class="footer-task">
        <p class="info-text town-text">
            <?= Html::encode($task->city->name) ?>
        </p>
        <p class="info-text category-text">
            <?= Html::encode($task->category->name) ?>
        </p>
        <?= Html::a('Смотреть Задание', ['tasks/view', 'id' => $task->id], ['class' => 'button button--black']) ?>
    </div>
</div>