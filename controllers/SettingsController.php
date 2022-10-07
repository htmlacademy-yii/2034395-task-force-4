<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class SettingsController extends Controller
{
    public function init(): void
    {
        parent::init();
        Yii::$app->user->loginUrl = ['auth/index'];
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }

    public function actionIndex(): Response|string
    {
        return $this->render('index');
    }
}