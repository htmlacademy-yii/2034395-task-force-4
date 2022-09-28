<?php

require_once Yii::$app->basePath . '/helpers/mainHelper.php';

use app\models\Task;
use app\models\Category;
use app\models\TasksFilterForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var Task[] $tasks
 * @var Category[] $categories
 * @var TasksFilterForm $filter
 * @var array $additionalParameters
 */

$categoryItems = ArrayHelper::map($categories, 'id', 'name');

$this->title = 'Task Force | New Tasks';
?>

<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php foreach ($tasks as $task): ?>
            <div class="task-card">
                <div class="header-task">
                    <?= Html::a(htmlspecialchars($task->title), ['tasks/view', 'id' => $task->id], ['class' => 'link link--block link--big']) ?>
                    <p class="price price--task"><?= htmlspecialchars($task->budget) ?> ₽</p>
                </div>
                <p class="info-text">
                    <span class="current-time"><?= normalizeDate($task->creation_date) ?> </span>назад
                </p>
                <p class="task-text">
                    <?= htmlspecialchars($task->details) ?>
                </p>
                <div class="footer-task">
                    <p class="info-text town-text">
                        <?= htmlspecialchars($task->city->name) ?>
                    </p>
                    <p class="info-text category-text">
                        <?= htmlspecialchars($task->category->name) ?>
                    </p>
                    <?= Html::a('Смотреть Задание', ['tasks/view', 'id' => $task->id], ['class' => 'button button--black']) ?>
                </div>
            </div>
        <?php endforeach; ?>
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
                    <h4 class="head-card">Категории</h4>
                    <?=
                    $form->field($filter, 'categoryIds')
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
                    <h4 class="head-card">Дополнительно</h4>
                    <?=
                    $form->field($filter, 'additional')
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
                    <h4 class="head-card">Период</h4>
                        <?=
                        $form->field($filter, 'period')
                            ->dropDownList([
                                1 => '1 час',
                                12 => '12 часов',
                                24 => '24 часа',
                            ], ['prompt' => 'Выберите период']);
                        ?>
                    <?= Html::submitInput('Искать', ['class' => 'button button--blue']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>