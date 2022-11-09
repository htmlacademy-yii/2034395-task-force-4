<?php

use app\models\Category;
use app\models\ChangeUserDataForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var ChangeUserDataForm $model
 * @var ActiveForm $form
 * @var Category[] $categories
 * @var string $type
 */

$categoryItems = ArrayHelper::map($categories, 'id', 'name');

$this->title = 'Task Force | Settings';
?>

<main class="main-content main-content--left container">
    <div class="left-menu left-menu--edit">
        <h3 class="head-main head-task">Настройки</h3>
        <ul class="side-menu-list">
            <li class="side-menu-item <?= $type === 'main' ? 'side-menu-item--active' : '' ?>">
                <?= Html::a('Мой профиль', ['settings/index', 'type' => 'main'], ['class' => 'link link--nav']); ?>
            </li>
            <li class="side-menu-item <?= $type === 'security' ? 'side-menu-item--active' : '' ?>">
                <?= Html::a('Безопасность', ['settings/index', 'type' => 'security'], ['class' => 'link link--nav']); ?>
            </li>
        </ul>
    </div>
    <div class="my-profile-form">
        <?php if ($type === 'main'): ?>
            <?= $this->render('_main', ['model' => $model, 'categoryItems' => $categoryItems]) ?>
        <?php elseif ($type === 'security'): ?>
            <?= $this->render('_security', ['model' => $model]) ?>
        <?php endif; ?>
    </div>
</main>