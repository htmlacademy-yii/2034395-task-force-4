<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Task;

class TasksController extends Controller
{
    public function actionIndex(): string
    {
        $modelsList = Task::find()->where(['status' => 'new'])->limit(5)->orderBy(['creation_date' => SORT_DESC])->all();

        return $this->render('index', [
            'modelsList' => $modelsList,
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
        $model = Task::findOne($id);

        return $this->render('view', ['model' => $model]);
    }
}