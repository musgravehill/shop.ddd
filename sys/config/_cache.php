<?php

return [
    'class' => 'yii\caching\MemCache',
    'servers' => [
        [
            'host' => 'localhost',
            'port' => 11211,
            'weight' => 100,
        ],
    ],
    'useMemcached' => true,
];
