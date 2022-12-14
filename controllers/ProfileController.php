<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\User;

class ProfileController extends AuthRequiredController
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
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }

    /**
     * Возвращает страницу просмотра конкретного профиля пользователя по его идентификатору
     *
     * @param int $id Идентификатор пользователя
     *
     * @throws NotFoundHttpException
     *
     * @return string
     */
    public function actionIndex(int $id): string
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $this->render('index', ['user' => $user]);
    }
}