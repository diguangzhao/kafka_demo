<?php
namespace Dea {

    class Core {

        private static $APP_SPACE = [
            'App'            => APP_PATH ,
            // 'Controller'     => APP_PATH . '/Controller',
            // 'Model'          => APP_PATH . '/Model',
            // 'Module'         => APP_PATH . '/Module'
        ];

        public function __construct() {

        }

        public static function autoload(string $class) {
            $parsePath = explode('\\', $class);

            // 判断是否为 Dea 命名空间
            if (array_shift($parsePath) == 'Dea') {

                if (!$parsePath[0]) {
                    throw new Exception("Error: Class {$class} Not Found! ", 1);
                }

                //是否在APP目录下
                if (array_key_exists($parsePath[0], self::$APP_SPACE)) {
                    array_shift($parsePath);
                    $path = APP_PATH . '/' . implode('/', $parsePath) . '.php';

                } else {
                    $path = SYSTEM_PATH . '/' . implode('/', $parsePath) . '.php';
                }

                if (file_exists($path)) {
                    require_once $path;
                }
                
            }

            return false;

        }

        public static function start() {
            error_reporting(E_ALL & ~E_NOTICE);
            spl_autoload_register('\Dea\Core::autoload');
            $composer_path = ROOT_PATH.'/vendor/autoload.php';
            if (file_exists($composer_path)) {
                require_once $composer_path;
            }

            \Dea\Config::setup();

            \Dea\Application::setup();
            \Dea\Application::main();


        }
    }

    class Application {

        public static function setup()
        {
            CGI::setup();
        }

        public static function main()
        {
            CGI::main();                   // 分派控制器
        }

        public static function shutdown()
        {
            CGI::shutdown();
        }

        public static function exception($e)
        {
            CGI::exception($e);
        }

    }
}

namespace {

    if (function_exists('URL')) {
        die('URL() was declared by other libraries!');
    } else {
        function URL($url = null, $query = null)
        {
            return \Dea\URI::url($url, $query);
        }
    }

    if (function_exists('V')) {
        die('V() was declared by other libraries!');
    } else {
        function V(string $path, $args = null)
        {
            return \Dea\IOC::construct('\Dea\View', $path, $args);
        }
    }

}