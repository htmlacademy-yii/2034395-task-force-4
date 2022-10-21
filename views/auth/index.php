<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\LoginForm;

/**
 * @var yii\web\View $this
 * @var LoginForm $model
 */

Yii::$app->layout = 'landing.php';

$this->title = 'Task Force | Login';
?>

<section class="modal enter-form form-modal" id="enter-form">
    <h2>Вход на сайт</h2>
    <?php
    $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => '{label}{error}{input}',
            'inputOptions' => ['class' => 'enter-form-email input input-middle'],
            'labelOptions' => ['class' => 'form-modal-description']
        ],
    ]);
    ?>
    <?=
    $form->field($model, 'email', ['enableAjaxValidation' => true])
        ->input('email')
        ->label('Email');
    ?>
    <?=
    $form->field($model, 'password', ['enableAjaxValidation' => true])
        ->passwordInput()
        ->label('Пароль');
    ?>
    <?= Html::submitButton('Войти', ['class' => 'button']) ?>
    <?php ActiveForm::end(); ?>
    <button class="form-modal-close" type="button">Закрыть</button>
</section>