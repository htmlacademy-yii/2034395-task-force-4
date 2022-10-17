<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;

$user = User::findOne(Yii::$app->user->id);

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => 'favicon.ico']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="page-header">
    <nav class="main-nav">
        <?=
        Html::a
        (
            Html::img
            (
                '@web/img/logotype.png',
                [
                    'class' => 'logo-image',
                    'width' => 227,
                    'height' => 60,
                    'alt' => 'taskforce'
                ]
            ),
            Url::to(['tasks/index']),
            ['class' => 'header-logo']
        );
        ?>

        <?php if ($user): ?>
            <div class="nav-wrapper">
                <?php
                $itemClass = 'list-item';
                $activeItemClass = 'list-item list-item--active';

                $items = [
                    ['label' => 'Новое', 'url' => ['tasks/index']],
                    ['label' => 'Мои задания', 'url' => ['tasks/owner']],
                    ['label' => 'Создать задание', 'url' => ['tasks/create']],
                    ['label' => 'Настройки', 'url' => ['settings/index']],
                ];
                ?>

                <ul class="nav-list">
                    <?php foreach ($items as $item): ?>
                        <li class="<?= Yii::$app->requestedRoute === $item['url'][0] ? $activeItemClass : $itemClass ?>">
                            <?= Html::a($item['label'], Url::to($item['url']), ['class' => 'link link--nav']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </nav>
    <?php if ($user): ?>
        <div class="user-block">
            <?=
            Html::a
            (
                Html::img
                (
                    $user->avatar_url,
                    [
                        'class' => 'logo-photo',
                        'width' => 55,
                        'height' => 55,
                        'alt' => 'Аватар'
                    ]
                ),
                Url::to(['profile/index'])
            );
            ?>
            <div class="user-menu">
                <p class="user-name">
                    <?= Html::encode($user->username) ?>
                </p>
                <div class="popup-head">
                    <ul class="popup-menu">
                        <li class="menu-item">
                            <?= Html::a('Настройки', ['settings/index'], ['class' => 'link']) ?>
                        </li>
                        <li class="menu-item">
                            <?= Html::a('Связаться с нами', ['site/contact'], ['class' => 'link']) ?>
                        </li>
                        <li class="menu-item">
                            <?= Html::a('Выход из системы', ['auth/logout'], ['class' => 'link']) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>
</header>

<?= Alert::widget() ?>
<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
