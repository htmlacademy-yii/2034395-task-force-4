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
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class RegistrationController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();
        Yii::$app->user->loginUrl = ['auth/index'];
    }

    /**
     * {@inheritDoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ],
                ]
            ]
        ];
    }

    /**
     * Возвращает страницу регистрации, обрабатывает POST запрос и создает аккаунт
     *
     * @return Response|string
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionIndex(string $token = ''): Response|string
    {
        $cities = City::find()->all();

        $model = new RegistrationForm();

        if ($token) {
            $oauth = new VkAuth();

            $_token = json_decode($token, true);
            $userData = $oauth->getUserData($_token);

            $city = City::findOne(['name' => $userData['city']['title'] ?? null]);

            $user = new User();

            $user->vk_id = $userData['id'] ?? null;
            $user->username = $userData['first_name'] ?? null;
            $user->email = $userData['email'] ?? null;
            $user->status = User::STATUS_FREE;
            $user->details = $userData['about'] ?? null;
            $user->avatar_url = $userData['photo_200_orig'];
            $user->is_executor = 1;

            if ($city) {
                $user->city_id = $city->id;
            }

            $user->birthday = $userData['bdate'] ?? null;
            $user->registration_date = date('Y-m-d H:i:s', time());

            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->access_token = Yii::$app->security->generateRandomString();

            if ($user->save(false)) {
                Yii::$app->user->login($user);
                return $this->redirect(Url::to(['tasks/index']));
            }
        }

        if ($model->load($this->request->post()) && $model->register()) {
            return $this->redirect(Url::to(['tasks/index']));
        }


        $model->password = null;
        $model->password_repeat = null;

        return $this->render('index', [
            'model' => $model,
            'cities' => $cities
        ]);
    }
}