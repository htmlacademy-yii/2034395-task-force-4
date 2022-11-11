<?php

use app\models\ChangeUserDataForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var ChangeUserDataForm $model
 * @var ActiveForm $form
 */

?>

<?php $form = ActiveForm::begin([]); ?>

<?= Html::tag('h3', 'Настройки безопасности', ['class' => 'head-main head-regular']) ?>

<?=
$form->field($model, 'old_password')
    ->passwordInput();
?>
<div class="half-wrapper">
    <?=
    $form->field($model, 'password')
        ->passwordInput()
        ->label('Новый пароль');
    ?>
    <?=
    $form->field($model, 'password_repeat')
        ->passwordInput();
    ?>
</div>

<?= Html::submitInput('Сохранить', ['class' => 'button button--blue']) ?>
<?php ActiveForm::end(); ?>
