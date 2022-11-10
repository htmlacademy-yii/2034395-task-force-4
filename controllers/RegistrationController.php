<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\City;
use app\models\RegistrationForm;
use app\models\VkAuth;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\Url;

class RegistrationController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();
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
                ],
                'denyCallback' => function ($rule, $action) {
                    Yii::$app->response->redirect(['tasks/index']);
                }
            ]
        ];
    }

    /**
     * Возвращает страницу регистрации, обрабатывает POST запрос и создает аккаунт
     *
     * @param string $code
     *
     * @throws StaleObjectException
     * @throws \Throwable
     * @throws VKApiException
     * @throws VKClientException
     *
     * @return Response|string
     */
    public function actionIndex(string $code = ''): Response|string
    {
        $cities = City::find()->all();

        $model = new RegistrationForm();

        if ($code) {
            $oauth = new VkAuth();
            $token = $oauth->getToken($code, 'registration');

            if (!$token['user_id']) {
                return $this->redirect(Url::to(['registration/index']));
            }

            $userData = $oauth->getUserData($token);

            if ($model->vkRegister($userData)) {
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