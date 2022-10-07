<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class SettingsController extends Controller
{
    public function actionIndex(): Response|string
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('index');
    }
}