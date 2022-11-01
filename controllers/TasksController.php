<?php

namespace app\controllers;

use app\models\CreateResponseForm;
use app\models\CreateTaskForm;
use app\models\EndTaskForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Task;
use app\models\Category;
use app\models\TasksFilterForm;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class TasksController extends Controller
{
    const ADDITIONAL_PARAMETERS = [
        'executor_id = null' => 'Без исполнителя'
    ];

    public function init(): void
    {
        parent::init();
        Yii::$app->user->loginUrl = ['auth/index'];
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'accept', 'decline'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['create', 'owner', 'submit', 'end', 'cancel'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => fn () => !Yii::$app->user->identity->is_executor,
                    ],
                ]
            ]
        ];
    }

    public function actionIndex(): Response|string
    {
        $tasks = Task::find()
            ->where(['status' => Task::STATUS_NEW])
            ->limit(5)
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $categories = Category::find()->all();
        $filterForm = new TasksFilterForm();

        if ($this->request->getIsPost()) {
            if ($filterForm->load($this->request->post()) && $filterForm->validate()) {
                $tasks = $filterForm->filter();
            }
        }

        return $this->render('index', [
            'tasks' => $tasks,
            'categories' => $categories,
            'filterForm' => $filterForm,
            'additionalParameters' => self::ADDITIONAL_PARAMETERS,
        ]);
    }

    public function actionSubmit(int $responseId, int $taskId): Response
    {
        $response = \app\models\Response::findOne($responseId);

        $response->task->executor_id = $response->executor_id;
        $response->task->status = Task::STATUS_IN_WORK;

        if ($response->task->update()) {
            foreach ($response->task->responses as $_response) {
                $_response->status = match ($_response->id) {
                    $responseId => \app\models\Response::STATUS_ACCEPTED,
                    default => \app\models\Response::STATUS_DECLINED
                };
            }
        }

        return $this->redirect(Url::to(['tasks/view', 'id' => $taskId]));
    }

    public function actionAccept(int $id): Response
    {
        $user = Yii::$app->user->identity;
        $task = Task::findOne($id);

        $createResponseForm = new CreateResponseForm();

        if ($this->request->getIsPost() && $createResponseForm->load($this->request->post())) {
            if (!$task->executor_id && !$user->getIsUserAcceptedTask($id)) {
                $createResponseForm->create();
            }
        }

        return $this->redirect(Url::to(['tasks/view', 'id' => $id]));
    }

    public function actionCancel(int $taskId, int $responseId): Response
    {
        $user = Yii::$app->user->identity;
        $task = Task::findOne($taskId);
        $response = \app\models\Response::findOne($responseId);

        if ($task->customer_id === $user->id) {
            $response->status = \app\models\Response::STATUS_DECLINED;
        }

        return $this->redirect(Url::to(['tasks/view', 'id' => $taskId]));
    }

    public function actionEnd(int $id): Response
    {
        $user = Yii::$app->user->identity;
        $task = Task::findOne($id);

        $endTaskForm = new EndTaskForm();

        if ($this->request->getIsPost() && $endTaskForm->load($this->request->post())) {
            if ($task->customer_id === $user->id && $task->status === Task::STATUS_IN_WORK) {
                $endTaskForm->end();
            }
        }

        return $this->redirect(Url::to(['tasks/view', 'id' => $id]));
    }

    public function actionDecline(int $id): Response
    {
        $user = Yii::$app->user->identity;
        $task = Task::findOne($id);

        if ($task->executor_id === $user->id && $task->status === Task::STATUS_IN_WORK) {
            $task->status = Task::STATUS_FAILED;
            $task->update();
        }

        return $this->redirect(Url::to(['tasks/view', 'id' => $id]));
    }

    public function actionOwner(): Response|string
    {
        return $this->render('owner');
    }

    public function actionCreate(): Response|string
    {
        $categories = Category::find()->all();

        $model = new CreateTaskForm();

        if ($this->request->getIsPost() && $model->load($this->request->post()) && $model->create()) {
            $lastTask = Task::find()->orderBy('id DESC')->one();

            return $this->redirect(['tasks/view', 'id' => $lastTask->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): Response|string
    {
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        $createResponseForm = new CreateResponseForm();
        $endTaskForm = new EndTaskForm();

        return $this->render('view', [
            'task' => $task,
            'createResponseForm' => $createResponseForm,
            'endTaskForm' => $endTaskForm
        ]);
    }
}