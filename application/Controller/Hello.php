<?php

namespace Dea\App\Controller;

class Hello extends \Dea\Controller\CGI\Base {

    public function __index($params = null) {
    	$db = \Dea\Database::db();
        $sql = "select * from hello";
        $res = $db->query($sql)->rows();
        foreach ($res as $key => $value) {
            var_dump($key);
            var_dump($value);
        }
    }

    public function actionA() {
    	return V('test');

    }
}