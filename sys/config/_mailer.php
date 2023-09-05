<?php

return [
    'class' => 'yii\swiftmailer\Mailer',
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'mail.hosting.reg.ru',
        'username' => 'robot@.com',
        'password' => '',
        'port' => '465',
        'encryption' => 'SSL',
    ],
];
