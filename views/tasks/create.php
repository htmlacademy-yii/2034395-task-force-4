<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\CreateTaskForm;
use app\models\Category;

/**
 * @var yii\web\View $this
 * @var ActiveForm $form
 * @var CreateTaskForm $model
 * @var Category[] $categories
 */

$categoriesItems = ArrayHelper::map($categories, 'id', 'name');

$this->title = 'Task Force | Create';
?>

<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
        <?php
        $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
            ]
        ])
        ?>
            <?= Html::tag('h3', 'Публикация нового задания', ['class' => 'head-main']) ?>
            <?=
            $form->field($model, 'title')
                ->textInput(['minlength' => 10])
                ->label('Опишите суть работы')
            ?>
            <?=
            $form->field($model, 'details')
                ->textarea(['minlength' => 30])
            ?>
            <?=
            $form->field($model, 'category_id')
                ->dropDownList($categoriesItems);
            ?>
            <?=
            $form->field($model, 'location')
                ->textInput(['class' => 'location-icon']);
            ?>
            <div class="half-wrapper">
                <?=
                $form->field($model, 'budget')
                    ->textInput(['class' => 'budget-icon']);
                ?>
                <?=
                $form->field($model, 'execution_date')
                    ->input('date');
                ?>
            </div>
            <?=
            $form->field($model, 'files[]', [
                'template' => "{label}" . "<label class='new-file'>" . "Добавить новый файл" . "\n {input}" . "</label>" . "\n {error}",
                'inputOptions' => ['style' => 'display: none;'],
            ])
                ->fileInput(['multiple' => true]);
            ?>
            <?= Html::submitInput('Опубликовать', ['class' => 'button button--blue']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</main>