<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mariadb_sdbn;dbname=sdbnv2',
    'username' => 'sdbn',
    'password' => 'sdbnpass',
    'charset' => 'utf8',
    // 'enableSchemaCache' => true,
    // 'schemaCacheDuration' => 3600,
    'attributes' => [
      PDO::ATTR_TIMEOUT => 10,
      PDO::ATTR_PERSISTENT => FALSE, //if true => mariaDB can drop long connection. None of the master DB servers is available.
    ],
];
