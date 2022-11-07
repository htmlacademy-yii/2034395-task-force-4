<?php

use app\models\Category;
use app\models\Task;
use app\models\TasksFilterForm;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/**
 * @var yii\web\View $this
 * @var Category[] $categories
 * @var TasksFilterForm $filterForm
 * @var array $additionalParameters
 * @var ActiveDataProvider $dataProvider
 */

$categoryItems = ArrayHelper::map($categories, 'id', 'name');

$this->title = 'Task Force | New Tasks';
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_task',
        ]) ?>
        <div class="pagination-wrapper">
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <?php
                $form = ActiveForm::begin([
                    'fieldConfig' => [
                        'template' => "{input}",
                    ],
                ]);
                ?>
                <?= Html::tag('h4', 'Категории', ['class' => 'head-card']); ?>
                <?=
                $form->field($filterForm, 'categoryIds')
                    ->checkboxList(
                        $categoryItems,
                        [
                            'class' => 'checkbox-wrapper',
                            'itemOptions' => [
                                'labelOptions' => ['class' => 'control-label']
                            ]
                        ]
                    );
                ?>
                <?= Html::tag('h4', 'Дополнительно', ['class' => 'head-card']); ?>
                <?=
                $form->field($filterForm, 'additional')
                    ->checkboxList(
                        $additionalParameters,
                        [
                            'class' => 'checkbox-wrapper',
                            'itemOptions' => [
                                'labelOptions' => ['class' => 'control-label']
                            ]
                        ]
                    );
                ?>
                <?= Html::tag('h4', 'Период', ['class' => 'head-card']); ?>
                <?=
                $form->field($filterForm, 'period')
                    ->dropDownList([
                        '-1 hour' => '1 час',
                        '-1 day' => 'Сутки',
                        '-1 week' => 'Неделя',
                    ], ['prompt' => 'Выберите период']);
                ?>
                <?= Html::submitInput('Искать', ['class' => 'button button--blue']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>