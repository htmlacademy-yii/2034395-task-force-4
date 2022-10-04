<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\User;

class ProfileController extends Controller
{
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['user' => $user]);
    }
}