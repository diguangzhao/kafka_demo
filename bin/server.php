<?php

namespace Dea;

class HttpServer {

    //单例模式：httpserver 实例
    public static $instance;

    public static $get;
    public static $post;
    public static $files;
    public static $header;

    public static $http;
    public static $server;

    public function __construct($host = '127.0.0.1', $port = '9301') {

        if (!self::$instance) {

            $http = new \swoole_http_server($host, $port);
            $http->set(
                [
                    'worker_num' => 16,
                    // 'daemonize' => true,
                    'max_request' => 10000,
                    'dispatch_mode' => 1,
                    'http_parse_post' => true,
                    'package_max_length' => 9991808,
                    'upload_tmp_dir' => '/tmp/uploadfiles'
                ]
            );

            define('SERVER_MODEL', 'sync_swoole');

            $http->on('WorkerStart' , array( $this , 'onWorkerStart'));

            $http->on("request", array($this, 'onRequest'));

            $http->start();

        } else {
            return self::$instance;
        }
    }

    public function onWorkerStart(\swoole_server $server, int $worker_id) {
        define('ROOT_PATH', dirname(__DIR__));
        ob_start();
        require_once ROOT_PATH . '/system/lib/cgi.php';
        ob_end_clean();
    }

    public function onRequest(\swoole_http_request $request, \swoole_http_response $response) {
        if(isset($request->get) ) {
            $_GET = $request->get;
        }
        if(isset($request->post)) {
            $_POST = $request->post;
        }
        if (isset($request->server)) {
            $_SERVER = array_change_key_case($request->server,CASE_UPPER);
        }

        // TODO request->files null? 太sb了吧！

        ob_start();

        try {
            Core::start();
        } catch (\Exception $e ) {
            var_dump( $e->getMessage() );
        }
        
        $result = ob_get_contents();

        ob_end_clean();

        // add Header
        
        // add cookies
        
        // set status
        $response->end($result);
    }

}

$server = new \Dea\HttpServer('0.0.0.0');
