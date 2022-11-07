<?php

use app\models\RegistrationForm;
use app\models\VkRegistrationForm;
use app\models\City;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var RegistrationForm $model
 * @var VkRegistrationForm $vkModel
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
            <a
                href="#"
                style="text-decoration: none; font-size: 1.2rem; color: black; border-bottom: 1px solid black"
                class="action-btn"
                data-action="reg_vk"
            >
                Продолжить через ВКонтакте
            </a>
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

<section class="pop-up pop-up--reg_vk pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Регистрация через ВКонтакте</h4>
        <p class="pop-up-text">
            Вы собираетесь зарегистрироваться на сайте, используя публичную информацию Вашей страницы ВКонтакте.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <?php $form = ActiveForm::begin([
                'action' => ['auth/vk'],
                'method' => 'post',
                'fieldConfig' => [
                    'template' => '{label}{error}{input}',
                ],
            ]); ?>

            <?=
            $form->field($vkModel, 'is_executor', ['template' => "{input}"])
                ->checkbox([
                    'checked' => true,
                    'label' => 'я собираюсь откликаться на заказы',
                    'labelOptions' => ['class' => 'control-label checkbox-label']
                ])
                ->label('Собираетесь ли вы откликаться на заказы?');
            ?>

            <?= Html::submitInput('Продолжить', ['class' => 'button button--pop-up button--blue']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>

<div class="overlay"></div>