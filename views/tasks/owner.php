<?php

use app\models\Task;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/**
 * @var yii\web\View $this
 * @var ActiveDataProvider $dataProvider
 * @var string $type
 */

$this->title = 'Task Force | My Tasks';

$title = match ($type) {
    'new' => 'Новые задания',
    'inWork' => 'Задания в процессе',
    'closed' => 'Закрытые задания'
}
?>

<main class="main-content container">
    <div class="left-menu">
        <h3 class="head-main head-task">Мои задания</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item <?= $type === 'new' ? 'side-menu-item--active' : '' ?>">
                <?=
                Html::a
                (
                    'Новые',
                    [
                        'tasks/owner',
                        'type' => 'new',
                        'status' => [Task::STATUS_NEW]
                    ],
                    ['class' => 'link link--nav']
                );
                ?>
            </li>
            <li class="side-menu-item <?= $type === 'inWork' ? 'side-menu-item--active' : '' ?>">
                <?=
                Html::a
                (
                    'В процессе',
                    [
                        'tasks/owner',
                        'type' => 'inWork',
                        'status' => [Task::STATUS_IN_WORK]
                    ],
                    ['class' => 'link link--nav']
                );
                ?>
            </li>
            <li class="side-menu-item <?= $type === 'closed' ? 'side-menu-item--active' : '' ?>">
                <?=
                Html::a
                (
                    'Закрытые',
                    [
                        'tasks/owner',
                        'type' => 'closed',
                        'status' => [
                            Task::STATUS_FAILED,
                            Task::STATUS_CANCELED,
                            Task::STATUS_PERFORMED
                        ]
                    ],
                    ['class' => 'link link--nav']
                );
                ?>
            </li>
        </ul>
    </div>
    <div class="left-column left-column--task">
        <h3 class="head-main head-regular">
            <?= Html::encode($title); ?>
        </h3>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_task',
            'layout' => "{items}\n<div class='pagination-wrapper'>{pager}</div>",
            'emptyText' => 'У вас нет заданий такого типа.',
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
                'linkOptions' => [
                    'class' => 'link link--page'
                ]
            ],
        ]) ?>
    </div>
</main>