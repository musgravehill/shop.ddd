<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=sdbnv2',
    'username' => 'sdbnv2',
    'password' => 'pass',
    'charset' => 'utf8',
    // 'enableSchemaCache' => true,
    // 'schemaCacheDuration' => 3600,
    'attributes' => [
      PDO::ATTR_TIMEOUT => 10,
      PDO::ATTR_PERSISTENT => FALSE, //if true => mariaDB can drop long connection. None of the master DB servers is available.
    ],
];
