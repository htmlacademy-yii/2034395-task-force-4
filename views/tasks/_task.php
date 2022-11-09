<?php

use app\helpers\MainHelpers;
use app\models\Task;
use yii\helpers\Html;

/**
 * @var Task $model
 */

?>

<div class="task-card">
    <div class="header-task">
        <?= Html::a(Html::encode($model->title), ['tasks/view', 'id' => $model->id],
            ['class' => 'link link--block link--big']) ?>
        <?php if ($model->budget > 0): ?>
            <p class="price price--task"><?= Html::encode($model->budget) ?> ₽</p>
        <?php endif; ?>
    </div>
    <p class="info-text">
        <span class="current-time"><?= MainHelpers::normalizeDate($model->creation_date) ?> </span>назад
    </p>
    <p class="task-text">
        <?= Html::encode($model->details) ?>
    </p>
    <div class="footer-task">
        <p class="info-text town-text">
            <?= Html::encode($model->city->name ?? 'Без локации') ?>
        </p>
        <p class="info-text category-text">
            <?= Html::encode($model->category->name) ?>
        </p>
        <?= Html::a('Смотреть Задание', ['tasks/view', 'id' => $model->id], ['class' => 'button button--black']) ?>
    </div>
</div>