<?php

use app\helpers\MainHelpers;
use app\models\Response;
use app\models\Task;
use yii\helpers\Html;

/**
 * @var Response $response
 * @var Task $task
 */

?>

<div class="response-card">
    <?=
    Html::img
    (
        $response->executor->avatar_url,
        [
            'class' => 'customer-photo',
            'width' => 146,
            'height' => 156,
            'alt' => "Фото заказчика"
        ]
    )
    ?>
    <div class="feedback-wrapper">
        <?=
        Html::a
        (
            Html::encode($response->executor->username),
            ['profile/index', 'id' => $response->executor_id],
            ['class' => 'link link--block link--big']
        );
        ?>
        <div class="response-wrapper">
            <div class="stars-rating small">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= $response->executor->executorRating): ?>
                        <span class="fill-star">&nbsp;</span>
                    <?php else: ?>
                        <span>&nbsp;</span>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <p class="reviews">
                <?php $reviewsCount = count($response->executor->executorReviews) ?>
                <?= $reviewsCount . ' ' . MainHelpers::getNounPluralForm($reviewsCount, 'отзыв', 'отзыва', 'отзывов') ?>
            </p>
        </div>
        <p class="response-message">
            <?= Html::encode($response->text) ?>
        </p>
    </div>
    <div class="feedback-wrapper">
        <p class="info-text">
            <span class="current-time"><?= MainHelpers::normalizeDate($response->creation_date) ?> </span>назад
        </p>
        <p class="price price--small"><?= Html::encode($response->price) ?> ₽</p>
    </div>
    <?php if ($task->customer_id === Yii::$app->user->id && $response->status === Response::STATUS_NEW): ?>
        <div class="button-popup">
            <?= Html::a('Принять', ['response/submit', 'responseId' => $response->id],
                ['class' => 'button button--blue button--small']); ?>
            <?= Html::a('Отказать', ['response/decline', 'responseId' => $response->id],
                ['class' => 'button button--orange button--small']); ?>
        </div>
    <?php endif; ?>
</div>
