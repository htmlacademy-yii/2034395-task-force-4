<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\User;

class ProfileController extends Controller
{
    public function actionIndex(): Response|string
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('index');
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): Response|string
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['user' => $user]);
    }
}