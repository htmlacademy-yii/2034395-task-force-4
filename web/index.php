<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();

if (count(\app\models\City::find()->all()) === 0) {
    $preparedFile = new SplFileObject('data/cities.csv');

    $preparedFile->seek(1);

    while ($preparedFile->valid()) {
        $data = $preparedFile->fgetcsv();
        $values = [];

        foreach ($data as $el) {
            $values[] = is_numeric($el) ? $el : "'$el'";
        }

        $params = implode(',', $values);

        $sql = "INSERT INTO city (`name`, `lat`, `long`) VALUES ($params)";

        Yii::$app->db->createCommand($sql)->execute();
    }
}