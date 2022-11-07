<?php

use app\models\Task;
use app\models\CreateResponseForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var View $this
 * @var CreateResponseForm $model
 * @var ActiveForm $form
 * @var Task $task
 */

?>

<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'action' => ['response/create', 'id' => $task->id],
                'method' => 'post',
                'fieldConfig' => [
                    'template' => '{label}{error}{input}',
                ],
            ]); ?>
            <?= $form->field($model, 'task_id', ['template' => '{input}'])->hiddenInput(['value' => $task->id]) ?>
            <?=
            $form->field($model, 'text')
                ->textarea()
                ->label('Ваш комментарий');
            ?>
            <?=
            $form->field($model, 'price')
                ->textInput();
            ?>
            <?= Html::submitInput('Завершить', ['class' => 'button button--pop-up button--blue']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
