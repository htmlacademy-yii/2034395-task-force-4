<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\Url;
use app\models\Task;
use app\models\Category;
use app\models\CreateResponseForm;
use app\models\CreateTaskForm;
use app\models\EndTaskForm;
use app\models\TasksFilterForm;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

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
                        'actions' => ['index', 'view', 'decline'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['create', 'owner', 'end', 'cancel'],
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
        $query = Task::find();

        $query->andFilterWhere(['status' => Task::STATUS_NEW]);

        $categories = Category::find()->all();
        $filterForm = new TasksFilterForm();

        if ($filterForm->load($this->request->post()) && $filterForm->validate()) {
            $query = $filterForm->filter();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);

        return $this->render('index', [
            'categories' => $categories,
            'filterForm' => $filterForm,
            'additionalParameters' => self::ADDITIONAL_PARAMETERS,
            'dataProvider' => $dataProvider
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
            'endTaskForm' => $endTaskForm,
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
     * Меняет статус задания на "Отменено" и отклоняет все отклики на него
     *
     * @param int $id Идентификатор задания
     *
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws StaleObjectException
     *
     * @return Response
     */
    public function actionCancel(int $id): Response
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