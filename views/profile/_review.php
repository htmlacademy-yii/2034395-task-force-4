<?php

use yii\helpers\Html;
use app\helpers\MainHelpers;
use app\models\Review;

/**
 * @var Review $review
 */

?>

<div class="response-card">
    <?= Html::img($review->customer->avatar_url, [
        'class' => 'customer-photo',
        'width' => 120,
        'height' => 127,
        'alt' => 'Фото заказчиков'
    ]) ?>
    <div class="feedback-wrapper">
        <?= Html::tag('p', Html::encode($review->text), ['class' => 'feedback']) ?>
        <?= Html::tag(
            'p',
            "Задание «" . Html::a(
                Html::encode($review->task->title),
                ['tasks/view', 'id' => $review->task->id],
                ['class' => 'link link--small']
            ) . "» выполнено",
            ['class' => 'task']
        ) ?>
    </div>
    <div class="feedback-wrapper">
        <div class="stars-rating small">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <?php if ($i <= $review->grade): ?>
                    <span class="fill-star">&nbsp;</span>
                <?php else: ?>
                    <span>&nbsp;</span>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <p class="info-text">
            <span class="current-time"><?= MainHelpers::normalizeDate($review->creation_date) ?> </span>назад
        </p>
    </div>
</div>
