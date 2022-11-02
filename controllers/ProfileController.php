<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\models\User;

class ProfileController extends Controller
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
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }

    /**
     * Возвращает страницу просмотра своего профиля
     *
     * @return Response|string
     */
    public function actionIndex(): Response|string
    {
        return $this->render('index');
    }

    /**
     * Возвращает страницу просмотра конкретного профиля пользователя по его идентификатору
     *
     * @param int $id Идентификатор пользователя
     *
     * @throws NotFoundHttpException
     *
     * @return Response|string
     */
    public function actionView(int $id): Response|string
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['user' => $user]);
    }
}