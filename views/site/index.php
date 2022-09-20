<?php

/** @var yii\web\View $this */
/** @var \app\models\Task $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Task Force';
?>

<div>
    <h2>Информация о задании:</h2>
    <ul>
        <li><?= Html::encode($model->attributeLabels()['title'] . ': ' . $model->title) ?></li>
        <li><?= Html::encode($model->attributeLabels()['details'] . ': ' . $model->details) ?></li>
        <li><?= Html::encode($model->attributeLabels()['status'] . ': ' . $model->status) ?></li>
        <li><?= Html::encode($model->attributeLabels()['location'] . ': ' . $model->location) ?></li>
        <li><?= Html::encode($model->attributeLabels()['budget'] . ': ' . $model->budget) ?></li>
        <li><?= Html::encode($model->attributeLabels()['creation_date'] . ': ' . $model->creation_date) ?></li>
        <li><?= Html::encode($model->attributeLabels()['execution_date'] . ': ' . $model->execution_date) ?></li>
    </ul>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'details') ?>
    <?= $form->field($model, 'status') ?>
    <?= $form->field($model, 'location') ?>
    <?= $form->field($model, 'budget') ?>
    <?= $form->field($model, 'creation_date') ?>
    <?= $form->field($model, 'execution_date') ?>

    <div>
        <?= Html::submitButton() ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>