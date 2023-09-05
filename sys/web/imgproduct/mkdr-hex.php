<?php

$dirs = array(
    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
    'a', 'b', 'c', 'd', 'e', 'f'
);

foreach ($dirs as $dir1) {
    $path1 = __DIR__ . '/' . $dir1;
    mkdir($path1, 0700);
    copy(__DIR__ . '/gitignore.txt', $path1 . '/.gitignore');
}
echo 'OK';

//  php /var/www/sdbn/sys/web/imgproduct/mkdr-hex.php 
