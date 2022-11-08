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
    public function actionIndex(): Response|string
    {
        $cities = City::find()->all();

        $model = new RegistrationForm();

        $model->scenario = 'default';

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