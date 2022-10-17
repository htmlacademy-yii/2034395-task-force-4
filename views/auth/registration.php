<?php

use app\models\RegistrationForm;
use app\models\City;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var RegistrationForm $model
 * @var City[] $cities
 * @var ActiveForm $form
 */

$cityItems = ArrayHelper::map($cities, 'id', 'name');

$this->title = 'Task Force | Registration';
?>

<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php
            $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                ]
            ]);
            ?>
            <?= Html::tag('h3', 'Регистрация нового пользователя', ['class' => 'head-main head-task']) ?>
            <?=
            $form->field($model, 'username')
                ->textInput()
                ->label('Ваше имя');
            ?>
            <div class="half-wrapper">
                <?=
                $form->field($model, 'email')
                    ->input('email')
                    ->label('Email');
                ?>
                <?=
                $form->field($model, 'city_id')
                    ->dropDownList($cityItems)
                    ->label('Город');
                ?>
            </div>
            <div class="half-wrapper">
                <?=
                $form->field($model, 'password')
                    ->passwordInput()
                    ->label('Пароль');
                ?>
            </div>
            <div class="half-wrapper">
                <?=
                $form->field($model, 'password_repeat')
                    ->passwordInput()
                    ->label('Повтор пароля');
                ?>
            </div>
            <?=
            $form->field($model, 'is_executor', ['template' => "{input}"])
                ->checkbox([
                    'checked' => true,
                    'label' => 'я собираюсь откликаться на заказы',
                    'labelOptions' => ['class' => 'control-label checkbox-label']
                ]);
            ?>
            <?= Html::submitInput('Создать аккаунт', ['class' => 'button button--blue']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</main>