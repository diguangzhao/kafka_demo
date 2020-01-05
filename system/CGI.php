<?php

namespace Dea;

class CGI {

	protected static $route;

	public static function setup() {
		URI::setup();
		self::$route = trim($_SERVER['PATH_INFO'] ? : $_SERVER['ORIG_PATH_INFO']);
	}

	public static function main() {
		$response = static::request(static::$route, [
            'get' => $_GET, 'post' => $_POST,
            'files' => $_FILES, 'route' => static::$route,
            'method' => $_SERVER['REQUEST_METHOD'],
            ])->execute();
        if ($response) {
            $response->output();
        }
	}

	public static function content() {
		return file_get_contents('php://input');
	}

    public static function request(string $route, array $env=[]) {
        $action = null;
        $args = [];

        $route = ltrim($route, '/');
        $routeArr = array_map('rawurldecode', explode('/', $route));

        $class = '\Dea\App\Controller' . '\\' . implode('\\', array_map('ucwords', $routeArr));
        if (!class_exists($class)) {
            while (true) {
                if ($action) {
                    $args[] = $action;
                }

                if ($routeArr) {
                    $action = array_pop($routeArr);
                } else {
                    break;
                }
                $class = '\Dea\App\Controller' . '\\' . implode('\\', array_map('ucwords', $routeArr));
                if (class_exists($class)) {
                    break;
                }

                $class .= "Index";

                if (class_exists($class)) {
                    break;
                }

            }
        }

        if (!$class || !class_exists($class, false)) {
            static::redirect('error/404');
        }

        $controller = \Dea\IOC::construct($class);
        $controller->env = $env;
        $controller->action = $action;
        $controller->params = $args;

        return $controller;

    }

    public static function redirect($url = '', $query = null)
    {
        // session_write_close();
        header('Location: '.URL($url, $query), true, 302);
        exit();
    }

    public static function route() {
        if (!self::$route) {
            self::setup();
        }
        return self::$route;
    }
}