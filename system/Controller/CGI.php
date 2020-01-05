<?php

namespace Dea\Controller;

abstract class CGI
{
    public $action;
    public $params;
    public $env;

    protected function __preAction($action, &$params)
    {
    	return true;
    }

    protected function __postAction($action, &$params, $response)
    {
    }

    public function execute()
    {	
        $action = $this->action;
        $action && $action = 'action' . ucwords($action);

        if (!method_exists($this, $action)) {
    		$action = '__index';
    		if (!method_exists($this, $action)) {
        		throw new Exception("Error Processing Request: action not found!", 1);
        	} else {
        		$this->action && $this->params = array_merge([$this->action], $this->params);
        	}
        }

        $params = $this->params;


        $this->action = $action;

        $response = $this->__preAction($action, $params);
        if ($response !== false) {
            $response = call_user_func_array([$this, $action], [$params]);
        }

        $response = $this->__postAction($action, $params, $response) ?: $response;

        return $response;
    }

    public function __index($params)
    {
        $this->__unknown($params);
    }

    public function __unknown($params)
    {
        echo "\e[1;34mDea\e[0m: unknown command.\n";
    }
}
