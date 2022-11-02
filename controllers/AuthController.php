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
    /**
     * Возвращает страницу авторизации, обрабатывает AJAX запрос, а также логинит пользователя на сайт
     *
     * @return Response|string|array
     */
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

        if ($model->load($this->request->post()) && $model->login()) {
            return $this->redirect(Url::to(['tasks/index']));
        }

        $model->password = null;

        return $this->render('index', ['model' => $model]);
    }

    /**
     * Закрывает сессию пользователя, очищает куки и выходит из аккаунта пользователя
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}