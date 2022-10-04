<?php

use app\helpers\MainHelpers;
use app\models\User;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var User $user
 */

$this->title = Html::encode("Task Force | $user->username");
?>

<main class="main-content container">
    <div class="left-column">
        <?= Html::tag('h3', Html::encode($user->username), ['class' => 'head-main']) ?>
        <div class="user-card">
            <div class="photo-rate">
                <?= Html::img($user->avatar_url, [
                    'class' => 'card-photo',
                    'width' => 191,
                    'height' => 190,
                    'alt' => 'Фото пользователя'
                ]); ?>
                <div class="card-rate">
                    <div class="stars-rating big">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $user->executorRating): ?>
                                <span class="fill-star">&nbsp;</span>
                            <?php else: ?>
                                <span>&nbsp;</span>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="current-rate"><?= $user->executorRating ?></span>
                </div>
            </div>
            <p class="user-description">
                <?= Html::encode($user->details) ?>
            </p>
        </div>
        <div class="specialization-bio">
            <?php if ($user->userCategories): ?>
                <div class="specialization">
                    <p class="head-info">Специализации</p>
                    <ul class="special-list">
                        <?php foreach ($user->userCategories as $userCategory): ?>
                            <?= Html::tag(
                                'li',
                                Html::a(
                                    $userCategory->category->name,
                                    '#',
                                    ['class' => 'link link--regular']
                                ),
                                ['class' => 'special-item']
                            ); ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <div class="bio">
                <p class="head-info">Био</p>
                <p class="bio-info">
                    <span class="town-info"><?= $user->city->name ?></span>,
                    <span class="age-info"><?= Html::encode($user->age) ?></span>
                    <?= MainHelpers::getNounPluralForm($user->age, 'год', 'года', 'лет') ?>
                </p>
            </div>
        </div>
        <?php if ($user->executorReviews): ?>
            <h4 class="head-regular">Отзывы заказчиков</h4>
            <?php foreach ($user->executorReviews as $executorReview): ?>
                <div class="response-card">
                    <?= Html::img($executorReview->customer->avatar_url, [
                        'class' => 'customer-photo',
                        'width' => 120,
                        'height' => 127,
                        'alt' => 'Фото заказчиков'
                    ]) ?>
                    <div class="feedback-wrapper">
                        <?= Html::tag('p', Html::encode($executorReview->text), ['class' => 'feedback']) ?>
                        <?= Html::tag(
                            'p',
                            "Задание «" . Html::a(
                                Html::encode($executorReview->task->title),
                                ['tasks/view', 'id' => $executorReview->task->id],
                                ['class' => 'link link--small']
                            ) . "» выполнено",
                            ['class' => 'task']
                        ) ?>
                    </div>
                    <div class="feedback-wrapper">
                        <div class="stars-rating small">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $executorReview->grade): ?>
                                    <span class="fill-star">&nbsp;</span>
                                <?php else: ?>
                                    <span>&nbsp;</span>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <p class="info-text">
                            <span class="current-time"><?= MainHelpers::normalizeDate($executorReview->creation_date) ?> </span>назад
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <h4 class="head-card">Статистика исполнителя</h4>
            <dl class="black-list">
                <dt>Всего заказов</dt>
                <dd><?= count($user->performedTasks) ?> выполнено, <?= count($user->failedTasks) ?> провалено</dd>
                <dt>Место в рейтинге</dt>
                <dd>25 место</dd>
                <dt>Дата регистрации</dt>
                <dd><?= date('d M, H:i', strtotime($user->registration_date)) ?></dd>
                <dt>Статус</dt>
                <dd><?= $user->statusLabel ?></dd>
            </dl>
        </div>
        <div class="right-card white">
            <h4 class="head-card">Контакты</h4>
            <ul class="enumeration-list">
                <?php if ($user->phone_number): ?>
                    <li class="enumeration-item">
                        <a href="#" class="link link--block link--phone">
                            <?= Html::encode($user->phone_number) ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user->email): ?>
                    <li class="enumeration-item">
                        <a href="#" class="link link--block link--email">
                            <?= Html::encode($user->email) ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user->telegram): ?>
                    <li class="enumeration-item">
                        <a href="#" class="link link--block link--tg">
                            <?= Html::encode($user->telegram) ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</main>