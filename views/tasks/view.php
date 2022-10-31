<?php

use app\models\Task;
use app\models\CreateResponseForm;
use yii\helpers\Html;
use app\helpers\MainHelpers;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var Task $task
 * @var CreateResponseForm $createResponseForm
 * @var ActiveForm $form
 */


$this->title = Html::encode("Task Force | $task->title ($task->budget ₽)");
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
            <p class="price price--big"><?= Html::encode($task->budget) ?> ₽</p>
        </div>
        <p class="task-description"><?= Html::encode($task->details) ?></p>
        <?php if ($task->status === Task::STATUS_NEW && Yii::$app->user->identity->is_executor): ?>
            <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <?php elseif ($task->status === Task::STATUS_IN_WORK && Yii::$app->user->identity->is_executor): ?>
            <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
            <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
        <?php endif; ?>
        <?php if ($task->city_id && $task->location): ?>
            <div class="task-map">
                <?=
                Html::img
                (
                    '@web/img/map.png',
                    [
                        'class' => 'map',
                        'width' => 725,
                        'height' => 346,
                        'alt' => $task->city->name
                    ]
                )
                ?>
                <p class="map-address town"><?= $task->city->name ?></p>
                <p class="map-address"><?= $task->location ?></p>
            </div>
        <?php endif; ?>
        <?php if (count($task->responses) > 0): ?>
            <h4 class="head-regular">Отклики на задание</h4>
        <?php endif; ?>
        <?php foreach (array_reverse($task->responses) as $response): ?>
            <?= $this->render('_response', ['response' => $response]) ?>
        <?php endforeach; ?>
    </div>
    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= $task->category->name ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= MainHelpers::normalizeDate($task->creation_date) ?> назад</dd>
                <dt>Срок выполнения</dt>
                <dd><?= date('d M, H:i', strtotime($task->execution_date)) ?></dd>
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
                                <?= MainHelpers::getFileNameFromUrl($file->url) ?>
                            </a>
                            <p class="file-size"><?= MainHelpers::normalizeFileSize($file->size) ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</main>
<?= $this->render('_decline') ?>
<?= $this->render('_end') ?>
<?= $this->render('_addResponse', ['model' => $createResponseForm, 'task' => $task]) ?>
<div class="overlay"></div>