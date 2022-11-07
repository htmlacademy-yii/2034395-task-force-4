<?php

namespace app\controllers;

use app\models\User;
use app\models\VkAuth;
use app\models\VkRegistrationForm;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;
use Yii;
use app\models\City;
use app\models\RegistrationForm;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class RegistrationController extends Controller
{
    /**
     * Возвращает страницу регистрации, обрабатывает POST запрос и создает аккаунт
     *
     * @return Response|string
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionIndex(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Url::to(['tasks/index']));
        }

        $cities = City::find()->all();

        $model = new RegistrationForm();
        $vkModel = new VkRegistrationForm();

        if ($model->load($this->request->post()) && $model->register()) {
            return $this->redirect(Url::to(['tasks/index']));
        }


        $model->password = null;
        $model->password_repeat = null;

        return $this->render('index', [
            'model' => $model,
            'vkModel' => $vkModel,
            'cities' => $cities
        ]);
    }

    /**
     * @throws VKApiException
     * @throws VKClientException
     */
    public function actionVk(string $token): Response
    {
        $token = json_decode($token, true);
        $oauth = new VkAuth();
        $userData = $oauth->getUserData($token);

        if (!$userData) {
            return $this->redirect(Url::to(['registration/index']));
        }

        $model = new VkRegistrationForm();

        if ($model->load($this->request->post())) {
            $model->username = $userData['first_name'] ?? null;
            $model->email = $token['email'] ?? null;

            if ($userData['bdate']) {
                $model->birthday = date('Y-m-d H:i:s', strtotime($userData['bdate']));
            }

            if ($userData['city']) {
                $city = City::findOne(['name' => $userData['city']['title']]);

                $model->city_id =  $city->id;
            }

            if ($model->register()) {
                $user = User::findOne(['email' => $token['email' ?? null]]);

                Yii::$app->user->login($user);

                return $this->redirect(Url::to(['tasks/index']));
            }
        }

        return $this->redirect(Url::to(['registration/index']));
    }
}