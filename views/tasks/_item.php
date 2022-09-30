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
        <?= Html::a(htmlspecialchars($task->title), ['tasks/view', 'id' => $task->id], ['class' => 'link link--block link--big']) ?>
        <p class="price price--task"><?= htmlspecialchars($task->budget) ?> ₽</p>
    </div>
    <p class="info-text">
        <span class="current-time"><?= MainHelpers::normalizeDate($task->creation_date) ?> </span>назад
    </p>
    <p class="task-text">
        <?= htmlspecialchars($task->details) ?>
    </p>
    <div class="footer-task">
        <p class="info-text town-text">
            <?= htmlspecialchars($task->city->name) ?>
        </p>
        <p class="info-text category-text">
            <?= htmlspecialchars($task->category->name) ?>
        </p>
        <?= Html::a('Смотреть Задание', ['tasks/view', 'id' => $task->id], ['class' => 'button button--black']) ?>
    </div>
</div>