<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\RegistrationForm;
use app\models\User;
use app\models\City;
use yii\helpers\Url;

class AuthController extends Controller
{
    public function actionIndex(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['tasks/index']));
        }

        $model = new User();

        if ($this->request->getIsPost() && $model->load($this->request->post()) && $model->validate()) {
            $user = User::findOne(['email' => $model->email]);

            if (Yii::$app->security->validatePassword($model->password, $user->password)) {
                Yii::$app->user->login($user);

                return $this->redirect(Url::to(['tasks/index']));
            }
        }

        return $this->render('index', ['model' => $model]);
    }

    public function actionRegistration(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['tasks/index']));
        }

        $cities = City::find()->all();

        $model = new RegistrationForm();

        if ($this->request->getIsPost() && $model->load($this->request->post()) && $model->register()) {
            return $this->redirect(Url::to(['tasks/index']));
        }


        $model->password = null;
        $model->password_repeat = null;

        return $this->render('registration', [
            'model' => $model,
            'cities' => $cities
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}