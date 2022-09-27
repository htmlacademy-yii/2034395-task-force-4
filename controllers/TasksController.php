<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Task;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $tasks = Task::find()
            ->where(['status' => Task::STATUS_NEW])
            ->limit(5)
            ->orderBy(['id' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

    public function actionOwner(): string
    {
        return $this->render('owner');
    }

    public function actionCreate(): string
    {
        return $this->render('create');
    }

    public function actionView(int $id): string
    {
        $task = Task::findOne($id);

        return $this->render('view', ['task' => $task]);
    }
}