<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Task;
use app\models\Category;
use app\models\TasksFilterForm;

class TasksController extends Controller
{
    const ADDITIONAL_PARAMETERS = [
        'executor_id = null' => 'Без исполнителя'
    ];

    public function actionIndex(): string
    {
        $tasks = Task::find()
            ->where(['status' => Task::STATUS_NEW])
            ->limit(5)
            ->orderBy(['id' => SORT_DESC])
            ->all();
        $categories = Category::find()->all();
        $filter = new TasksFilterForm();

        if (Yii::$app->request->getIsPost() && $filter->load(Yii::$app->request->post()) && $filter->validate()) {
            $tasks = Task::find()->where(['status' => Task::STATUS_NEW]);

            if (!empty($filter->categoryIds)) {
                $tasks = $tasks->andWhere(['category_id' => $filter->categoryIds]);
            }

            if (!empty($filter->additional)) {
                foreach ($filter->additional as $condition) {
                    $tasks = $tasks->andWhere($condition);
                }
            }

            if ($filter->period > 0) {
                $timestamp = strtotime("-$filter->period hour");
                $datetime = date("Y-m-d H:i:s", $timestamp);

                $tasks->andWhere(['>', 'creation_date', $datetime]);
            }

            $tasks = $tasks->limit(5)
                ->orderBy(['id' => SORT_DESC])
                ->all();
        }

        return $this->render('index', [
            'tasks' => $tasks,
            'categories' => $categories,
            'filter' => $filter,
            'additionalParameters' => self::ADDITIONAL_PARAMETERS,
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