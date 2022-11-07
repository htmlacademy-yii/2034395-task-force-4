<?php

namespace app\controllers;

use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @var $this Yii
 */

class ResponseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();
        Yii::$app->user->loginUrl = ['auth/index'];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['submit', 'decline'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => fn () => !Yii::$app->user->identity->is_executor,
                    ],
                ]
            ]
        ];
    }

    /**
     * Меняет статус отклика на "Принят" по его идентификатору, отклоняя остальные отклики на данное задание
     *
     * @param int $responseId Идентификатор отклика
     *
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return Response
     */
    public function actionSubmit(int $responseId): Response
    {
        $response = \app\models\Response::findOne($responseId);

        $response->submit();

        return $this->redirect(Url::to(['tasks/view', 'id' => $response->task_id]));
    }

    /**
     * Создает новый отклик на задание по его идентификатору
     *
     * @param int $id Идентификатор задания
     *
     * @return Response
     */
    public function actionCreate(int $id): Response
    {
        $response = new \app\models\Response();

        $response->create();

        return $this->redirect(Url::to(['tasks/view', 'id' => $id]));
    }

    /**
     * Меняет статус отклика на "Отклонен" по его идентификатору
     *
     * @param int $responseId Идентификатор отклика
     *
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return Response
     */
    public function actionDecline(int $responseId): Response
    {
        $response = \app\models\Response::findOne($responseId);

        $response->cancel();

        return $this->redirect(Url::to(['tasks/view', 'id' => $response->task_id]));
    }
}