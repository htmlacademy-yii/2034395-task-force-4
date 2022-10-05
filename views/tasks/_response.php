<?php

use yii\helpers\Html;
use app\helpers\MainHelpers;
use app\models\Response;

/**
 * @var Response $response
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
            ['profile/view', 'id' => $response->executor_id],
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
        <p class="info-text"><span
                    class="current-time"><?= MainHelpers::normalizeDate($response->creation_date) ?> </span>назад</p>
        <p class="price price--small"><?= Html::encode($response->price) ?> ₽</p>
    </div>
    <div class="button-popup">
        <a href="#" class="button button--blue button--small">Принять</a>
        <a href="#" class="button button--orange button--small">Отказать</a>
    </div>
</div>
