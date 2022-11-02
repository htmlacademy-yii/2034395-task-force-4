<?php
use yii\helpers\Html;
use app\models\Task;

/**
 * @var Task $task
 */
?>

<section class="pop-up pop-up--cancel pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отмена задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отменить это задание.<br>
            Это действие отклонит отклики и безвозвратно переведет задание в статус "Отменено".
        </p>
        <?= Html::a('Отменить', ['tasks/cancelt', 'id' => $task->id], ['class' => 'button button--pop-up button--orange']); ?>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
