<?php

return [
    'class' => 'yii\web\AssetManager',
    'bundles' => [
        'yii\web\YiiAsset' => [
            'js' => [
                'yii.js'
            ]
        ],
        'yii\web\JqueryAsset' => [
            'js' => [
                'jquery.min.js',
            ]
        ],
    ],
    'appendTimestamp' => true,
];
