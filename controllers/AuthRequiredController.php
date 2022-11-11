<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class AuthRequiredController extends Controller
{
    /**
     * {@inheritDoc}
     */
    public function init(): void
    {
        parent::init();
        Yii::$app->user->loginUrl = ['auth/index'];
    }
}