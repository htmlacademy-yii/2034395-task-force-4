<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this Yii
 */

class AuthController extends Controller
{
    public function actionIndex(): Response|string|array
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['tasks/index']));
        }

        $model = new LoginForm();

        if ($this->request->isAjax && $model->load($this->request->post())) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->getIsPost() && $model->load($this->request->post())) {
            if ($model->login()) {
                return $this->redirect(Url::to(['tasks/index']));
            }
        }

        $model->password = null;

        return $this->render('index', ['model' => $model]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}