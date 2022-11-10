<?php

use app\helpers\NormalizeHelpers;
use app\models\CreateResponseForm;
use app\models\EndTaskForm;
use app\models\Task;
use phpnt\yandexMap\YandexMaps;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var Task $task
 * @var CreateResponseForm $createResponseForm
 * @var EndTaskForm $endTaskForm
 * @var ActiveForm $form
 */

$items = [];

if ($task->city_id && $task->location) {
    $items = [
        [
            'latitude' => $task->location_lat,
            'longitude' => $task->location_long,
            'options' => [
                [
                    'hintContent' => $task->city->name,
                    'balloonContentHeader' => $task->title,
                    'balloonContentBody' => $task->details,
                    'balloonContentFooter' => 'TaskForce, 2022'
                ],
                [
                    'preset' => 'islands#icon',
                    'iconColor' => '#19a111'
                ]
            ]
        ]
    ];
}

$this->title = Html::encode("Task Force | $task->title ($task->budget ₽)");
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
            <?php if ($task->budget > 0): ?>
                <p class="price price--big"><?= Html::encode($task->budget) ?> ₽</p>
            <?php endif; ?>
        </div>
        <p class="task-description"><?= Html::encode($task->details) ?></p>
        <?php if ($task->status === Task::STATUS_NEW && Yii::$app->user->identity->is_executor && !Yii::$app->user->identity->getIsUserAcceptedTask($task->id)): ?>
            <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <?php endif; ?>
        <?php if ($task->status === Task::STATUS_IN_WORK && $task->executor_id === Yii::$app->user->id): ?>
            <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
        <?php endif; ?>
        <?php if ($task->status === Task::STATUS_IN_WORK && $task->customer_id === Yii::$app->user->id): ?>
            <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
        <?php endif; ?>
        <?php if ($task->status === Task::STATUS_NEW && $task->customer_id === Yii::$app->user->id): ?>
            <a href="#" class="button button--pink action-btn" data-action="cancel">Отменить задание</a>
        <?php endif; ?>
        <?php if ($task->city_id && $task->location): ?>
            <div class="task-map">
                <?=
                YandexMaps::widget([
                    'myPlacemarks' => $items,
                    'mapOptions' => [
                        'center' => [$task->location_lat, $task->location_long],
                        'zoom' => 15,
                        'controls' => ['zoomControl'],
                        'control' => [
                            'zoomControl' => [
                                'top' => 75,
                                'left' => 5,
                            ]
                        ]
                    ],
                    'disableScroll' => true,
                    'windowWidth' => '725px',
                    'windowHeight' => '346px'
                ]);
                ?>
                <p class="map-address town"><?= $task->city->name ?></p>
                <p class="map-address"><?= $task->location ?></p>
            </div>
        <?php endif; ?>
        <?php if (count($task->responses) > 0): ?>
            <h4 class="head-regular">Отклики на задание</h4>
        <?php endif; ?>
        <?php foreach (array_reverse($task->responses) as $response): ?>
            <?php
            if ($response->task->customer_id === Yii::$app->user->id ||
                $response->executor_id === Yii::$app->user->id):
                ?>
                <?= $this->render('_response', ['response' => $response, 'task' => $task]) ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= $task->category->name ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= NormalizeHelpers::normalizeDate($task->creation_date) ?> назад</dd>
                <?php if ($task->execution_date): ?>
                    <dt>Срок выполнения</dt>
                    <dd><?= date('d M, H:i', strtotime($task->execution_date)) ?></dd>
                <?php endif; ?>
                <dt>Статус</dt>
                <dd><?= $task->statusLabel ?></dd>
            </dl>
        </div>
        <?php if (count($task->taskFiles) > 0): ?>
            <div class="right-card white file-card">
                <h4 class="head-card">Файлы задания</h4>
                <ul class="enumeration-list">
                    <?php foreach ($task->taskFiles as $taskFile): ?>
                        <?php $file = $taskFile->file ?>
                        <li class="enumeration-item">
                            <a href="<?= $file->url ?>" target="_blank" class="link link--block link--clip">
                                <?= NormalizeHelpers::getFileNameFromUrl($file->url) ?>
                            </a>
                            <p class="file-size"><?= NormalizeHelpers::normalizeFileSize($file->size) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</main>
<?= $this->render('_decline', ['task' => $task]) ?>
<?= $this->render('_end', ['model' => $endTaskForm, 'task' => $task]) ?>
<?= $this->render('_addResponse', ['model' => $createResponseForm, 'task' => $task]) ?>
<?= $this->render('_cancel', ['task' => $task]) ?>
<div class="overlay"></div>