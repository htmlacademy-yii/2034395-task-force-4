<?php

use app\models\EndTaskForm;
use app\models\Task;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var View $this
 * @var EndTaskForm $model
 * @var ActiveForm $form
 * @var Task $task
 */
?>

<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'action' => ['tasks/end', 'id' => $task->id],
                'method' => 'post',
                'fieldConfig' => [
                    'template' => '{label}{input}{error}',
                ],
            ]); ?>
            <?=
            $form->field($model, 'task_id', ['template' => '{input}'])
                ->hiddenInput(['value' => $task->id]);
            ?>
            <?=
            $form->field($model, 'customer_id', ['template' => '{input}'])
                ->hiddenInput(['value' => $task->customer_id]);
            ?>
            <?=
            $form->field($model, 'executor_id', ['template' => '{input}'])
                ->hiddenInput(['value' => $task->executor_id]);
            ?>

            <?=
            $form->field($model, 'text')
                ->textarea()
                ->label('Ваш комментарий');
            ?>
            <?= Html::tag('p', 'Оценка работы', ['class' => 'completion-head control-label']) ?>
            <?=
            $form->field($model, 'grade', ['template' => '{input}'])
                ->hiddenInput(['value' => 0]);
            ?>
            <div class="stars-rating big active-stars">
                <span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span>
            </div>
            <?= Html::submitInput('Завершить', ['class' => 'button button--pop-up button--blue']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
