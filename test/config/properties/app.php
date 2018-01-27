<?php
return [
    "version"      => '1.0',
    'autoInitBean' => true,
    'beanScan'     => [
        'Swoft\\Redis\\Test\\Testing' => BASE_PATH . "/Testing"
    ],
    'cache'        => require __DIR__ . DS . "cache.php",
];
