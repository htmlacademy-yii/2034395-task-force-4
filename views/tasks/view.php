<?php

/**
 * @var yii\web\View $this
 * @var Task $task
 */

use app\models\Task;
use yii\helpers\Html;
use app\helpers\MainHelpers;

$this->title = Html::encode("Task Force | $task->title ($task->budget ₽)");
?>

<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= Html::encode($task->title) ?></h3>
            <p class="price price--big"><?= Html::encode($task->budget) ?> ₽</p>
        </div>
        <p class="task-description"><?= Html::encode($task->details) ?></p>
        <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
        <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
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
        <?php foreach ($task->responses as $response): ?>
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
                    <?php foreach ($task->taskFiles as $file): ?>
                        <li class="enumeration-item">
                            <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                            <p class="file-size">356 Кб</p>
                        </li>
                        <li class="enumeration-item">
                            <a href="#" class="link link--block link--clip">information.docx</a>
                            <p class="file-size">12 Кб</p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</main>
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <form>
                <div class="form-group">
                    <label class="control-label" for="completion-comment">Ваш комментарий</label>
                    <textarea id="completion-comment"></textarea>
                </div>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars">
                    <span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
                </div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <form>
                <div class="form-group">
                    <label class="control-label" for="addition-comment">Ваш комментарий</label>
                    <textarea id="addition-comment"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label" for="addition-price">Стоимость</label>
                    <input id="addition-price" type="text">
                </div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>