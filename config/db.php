<?php

$params = require __DIR__ . '/params.php';

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . $params['dbHost'] .  ';dbname=' . $params['dbName'],
    'username' => $params['dbUsername'],
    'password' => $params['dbPassword'],
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
