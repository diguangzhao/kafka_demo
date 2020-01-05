<?php

$config['init'] = [
    'http' =>     [
                    'worker_num' => 16,
                    // 'daemonize' => true,
                    'max_request' => 10000,
                    'dispatch_mode' => 1
                ]
];
