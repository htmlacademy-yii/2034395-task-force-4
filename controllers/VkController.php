<?php

namespace app\controllers;

use Yii;
use app\models\City;
use app\models\User;
use app\models\VkAuth;
use app\models\RegistrationForm;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;
use yii\db\StaleObjectException;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class VkController extends Controller
{
    public function actionAuth(): void
    {
        $oauth = new VkAuth();
        $oauth->auth();
    }

    public function actionRedirect(string $code): Response
    {
        $oauth = new VkAuth();
        $token = $oauth->getToken($code);

        $userData = $oauth->getUserData($token);

        if (!$userData) {
            return $this->redirect(Url::to(['registration/index']));
        }

        var_dump($token);

        $user = User::findOne(['vk_id' => $token['user_id'] ?? null]);

        if (!$user) {
            return $this->redirect(Url::to(['vk/register', 'token' => json_encode($token)]));
        }

        Yii::$app->user->login($user);
        return $this->redirect(Url::to(['tasks/index']));
    }

    /**
     * @param string $token
     *
     * @throws VKApiException
     * @throws VKClientException
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return Response
     */
    public function actionRegister(string $token): Response
    {
        $token = json_decode($token, true);
        $oauth = new VkAuth();
        $userData = $oauth->getUserData($token);

        if (!$userData) {
            return $this->redirect(Url::to(['registration/index']));
        }

        $model = new RegistrationForm();

        $model->scenario = 'vk';

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