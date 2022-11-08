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

        if (!$token['user_id']) {
            return $this->redirect(Url::to(['registration/index']));
        }

        $user = User::findOne(['vk_id' => $token['user_id']]);

        if (!$user) {
            return $this->redirect(Url::to(['registration/index', 'token' => json_encode($token)]));
        }

        Yii::$app->user->login($user);
        return $this->redirect(Url::to(['tasks/index']));
    }
}