<?php

namespace Dea\Controller\CGI;

class Base extends \Dea\Controller\CGI {
	public function __preAction($action, &$params)
    {
    	return true;
    }

    public function __postAction($action, &$params, $response)
    {
        $response = \Dea\IoC::construct('\Dea\CGI\Response\HTML', $response);

        return $response;
    }
}