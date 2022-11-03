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
                        'actions' => ['index', 'view', 'accept', 'decline'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['create', 'owner', 'submit', 'end', 'cancelt', 'cancelr'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => fn () => !Yii::$app->user->identity->is_executor,
                    ],
                ]
            ]
        ];
    }

    /**
     * Возвращает страницу просмотра заданий, предварительно фильтруя их
     *
     * @return Response|string
     */
    public function actionIndex(): Response|string
    {
        $tasks = Task::find()
            ->where(['status' => Task::STATUS_NEW])
            ->limit(5)
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $categories = Category::find()->all();
        $filterForm = new TasksFilterForm();

        if ($filterForm->load($this->request->post()) && $filterForm->validate()) {
            $tasks = $filterForm->filter();
        }

        return $this->render('index', [
            'tasks' => $tasks,
            'categories' => $categories,
            'filterForm' => $filterForm,
            'additionalParameters' => self::ADDITIONAL_PARAMETERS,
        ]);
    }

    /**
     * Возвращает страницу просмотра заданий, предварительно фильтруя их
     *
     * @param string $type Тип страницы для отображения стилей и названия
     * @param array $status Массив статусов для фильтрации заданий
     *
     * @return Response|string
     */
    public function actionOwner(string $type, array $status): Response|string
    {
        $tasks = Task::findAll(['customer_id' => Yii::$app->user->id, 'status' => $status]);

        return $this->render('owner', ['tasks' => $tasks, 'type' => $type]);
    }

    /**
     * Возвращает страницу создания задания, обрабатывает пришедший POST запрос
     *
     * @return Response|string
     */
    public function actionCreate(): Response|string
    {
        $categories = Category::find()->all();

        $model = new CreateTaskForm();

        if ($model->load($this->request->post()) && $model->create()) {
            $lastTask = Task::find()->orderBy('id DESC')->one();

            return $this->redirect(['tasks/view', 'id' => $lastTask->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories
        ]);
    }

    /**
     * Возвращает страницу просмотра задания по его идентификатору
     *
     * @param int $id Идентификатор задания
     *
     * @throws NotFoundHttpException
     *
     * @return Response|string
     */
    public function actionView(int $id): Response|string
    {
        $task = $this->find($id);

        $createResponseForm = new CreateResponseForm();
        $endTaskForm = new EndTaskForm();

        return $this->render('view', [
            'task' => $task,
            'createResponseForm' => $createResponseForm,
            'endTaskForm' => $endTaskForm
        ]);
    }

    /**
     * Возвращает задание по идентификатору, либо ошибку, если такое задание не найдено
     *
     * @param int $id Идентификатор задания
     *
     * @throws NotFoundHttpException
     *
     * @return Task
     */
    public function find(int $id): Task
    {
        return Task::findOne($id) ?? throw new NotFoundHttpException();
    }

    /**
     * Меняет статус отклика на "Принят" по его идентификатору, отклоняя остальные отклики на данное задание
     *
     * @param int $responseId Идентификатор отклика
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
    public function actionAccept(int $id): Response
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
     * @return Response
     */
    public function actionCancelr(int $responseId): Response
    {
        $response = \app\models\Response::findOne($responseId);

        $response->cancel();

        return $this->redirect(Url::to(['tasks/view', 'id' => $response->task_id]));
    }

    /**
     * Меняет статус задания на "Отменено" и отклоняет все отклики на него
     *
     * @param int $id Идентификатор задания
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     *
     */
    public function actionCancelt(int $id): Response
    {
        $task = $this->find($id);

        $task->cancel();

        return $this->redirect(Url::to(['tasks/view', 'id' => $id]));
    }

    /**
     * Меняет статус задания на "Выполнено"
     *
     * @param int $id Идентификатор задания
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     *
     */
    public function actionEnd(int $id): Response
    {
        $task = $this->find($id);

        $task->end();

        return $this->redirect(Url::to(['tasks/view', 'id' => $id]));
    }

    /**
     * Меняет статус задания на "Провалено" и отклоняет все отклики на него
     *
     * @param int $id Идентификатор задания
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     *
     */
    public function actionDecline(int $id): Response
    {
        $task = $this->find($id);

        $task->decline();

        return $this->redirect(Url::to(['tasks/view', 'id' => $id]));
    }
}