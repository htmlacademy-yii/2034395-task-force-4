<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Task;
use app\models\Category;
use app\models\TasksFilterForm;
use yii\web\Response;

class TasksController extends Controller
{
    const ADDITIONAL_PARAMETERS = [
        'executor_id = null' => 'Без исполнителя'
    ];

    public function actionIndex(): Response|string
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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

    public function actionOwner(): Response|string
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('owner');
    }

    public function actionCreate(): Response|string
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('create');
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): Response|string
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException();
        }

        return $this->render('view', ['task' => $task]);
    }
}