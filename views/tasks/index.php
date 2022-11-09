<?php

use app\models\Category;
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
            'layout' => "{items}\n<div class='pagination-wrapper'>{pager}</div>",
            'emptyText' => 'Новых заданий пока нет.',
            'pager' => [
                'hideOnSinglePage' => true,
                'maxButtonCount' => 3,
                'options' => [
                    'class' => 'pagination-list',
                ],
                'nextPageLabel' => '',
                'prevPageLabel' => '',
                'nextPageCssClass' => 'pagination-item mark',
                'prevPageCssClass' => 'pagination-item mark',
                'pageCssClass' => 'pagination-item',
                'activePageCssClass' => 'pagination-item--active',
                'linkOptions' => [
                    'class' => 'link link--page'
                ]
            ],
        ]) ?>
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