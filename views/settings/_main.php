<?php

use app\models\ChangeUserDataForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var ChangeUserDataForm $model
 * @var ActiveForm $form
 * @var array $categoryItems
 */


$user = Yii::$app->user->identity;
?>

<?php $form = ActiveForm::begin([]) ?>

<?= Html::tag('h3', 'Мой профиль', ['class' => 'head-main head-regular']) ?>
<div class="photo-editing">
    <div>
        <?= Html::tag('p', 'Аватар', ['class' => 'form-label']); ?>
        <?=
        Html::img($user->avatar_url, [
            'class' => 'avatar-preview',
            'width' => 83,
            'height' => 83,
            'alt' => 'Аватар'
        ]);
        ?>
    </div>
    <?= $form->field($model, 'avatar', [
        'template' =>
            "<label class='button button--black'>"
            . "Сменить аватар"
            . "{input}"
            . "</label>"
            . "\n {error}",
        'inputOptions' => ['style' => 'display: none;'],
    ])
        ->fileInput([]);
    ?>
</div>
<?=
$form->field($model, 'username')
    ->textInput()
    ->label('Ваше имя');
?>
<div class="half-wrapper">
    <?=
    $form->field($model, 'email')
        ->input('email');
    ?>
    <?=
    $form->field($model, 'birthday')
        ->input('date');
    ?>
</div>
<div class="half-wrapper">
    <?=
    $form->field($model, 'phone')
        ->input('tel');
    ?>
    <?=
    $form->field($model, 'telegram')
        ->textInput();
    ?>
</div>
<?=
$form->field($model, 'details')
    ->textarea();
?>
<?php if (Yii::$app->user->identity->is_executor): ?>
    <?=
    $form->field($model, 'categories')
        ->checkboxList(
            $categoryItems,
            [
                'class' => 'checkbox-profile',
                'itemOptions' => [
                    'labelOptions' => ['class' => 'control-label']
                ]
            ]
        );
    ?>
<?php endif; ?>
<?= Html::submitInput('Сохранить', ['class' => 'button button--blue']) ?>
<?php ActiveForm::end(); ?>
