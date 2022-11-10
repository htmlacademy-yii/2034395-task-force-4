<?php

namespace app\controllers;

use app\models\Category;
use app\models\UserCategory;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\helpers\Url;
use app\models\ChangeUserDataForm;

class SettingsController extends AuthRequiredController
{
    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();
    }

    /**
     * {@inheritDoc}}
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
     * Возвращает страницу просмотра настроек аккаунта
     *
     * @var string $type
     *
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return Response|string
     *
     */
    public function actionIndex(string $type = 'main'): Response|string
    {
        $user = Yii::$app->user->identity;
        $categories = Category::find()->all();

        $model = new ChangeUserDataForm();

        if ($type === 'security') {
            $model->scenario = $model::SCENARIO_SECURITY;
        }

        $model->username = $user->username;
        $model->email = $user->email;
        $model->birthday = date('Y-m-d', strtotime($user->birthday));
        $model->phone = $user->phone_number;
        $model->telegram = $user->telegram;
        $model->details = $user->details;

        foreach (UserCategory::findAll(['user_id' => $user->id]) as $userCategory) {
            $model->categories[] = $userCategory->category_id;
        }

        if ($model->load($this->request->post()) && $model->change()) {
            return $this->redirect(Url::to(['profile/index', 'id' => $user->id]));
        }

        $model->old_password = null;
        $model->password = null;
        $model->password_repeat = null;

        return $this->render('index', ['model' => $model, 'categories' => $categories, 'type' => $type]);
    }
}