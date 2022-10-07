<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
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

        return $this->render('index');
    }

    public function actionRegistration(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['tasks/index']));
        }

        $cities = City::find()->all();

        $user = new User();

        $user->scenario = User::SCENARIO_REGISTRATION;

        if ($this->request->getIsPost()) {
            if ($user->load($this->request->post()) && $user->validate()) {
                $user->auth_key = Yii::$app->security->generateRandomString();
                $user->access_token = Yii::$app->security->generateRandomString();
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                $user->registration_date = date('Y-m-d H:i:s', time());

                $user->save(false);

                Yii::$app->user->login($user);

                return $this->redirect(Url::to(['tasks/index']));
            }
        }


        $user->password = null;
        $user->password_repeat = null;

        return $this->render('registration', [
            'user' => $user,
            'cities' => $cities
        ]);
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}