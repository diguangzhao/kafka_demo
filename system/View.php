<?php

namespace Dea;

class View {
    
    protected $_path;
    protected $_vars;

    public function __construct($path, $vars = null) {
        $this->_path = $path;
    }

    public function __get($key) {
        return $this->_vars[$key];
    }

    public function __set($key, $value) {
        if ($value === null) {
            unset($this->_vars[$key]);
        } else {
            $this->_vars[$key] = $value;
        }
    }

    public function __unset($key)
    {
        unset($this->_vars[$key]);
    }

    public function __isset($key)
    {
        return isset($this->_vars[$key]);
    }

    public function __toString()
    {
        if ($this->_ob_cache !== null) {
            return $this->_ob_cache;
        }

        $path = $this->_path;
        $view = \Dea\Config::get('system.view');

        $engines = $view['engines'];

        foreach ($engines as $ext => $engine) {
            $basepath = $engine['path'] ? : VIEW_PATH;
            $realPath = "$basepath/$path.$ext";
            if (file_exists($realPath)) {
                break;
            }
        }

        if ($engine && $realPath) {
            $class = '\Dea\View\\'.$engine['engine'];
            $output = \Dea\IoC::construct($class, $realPath, $this->_vars ?: []);
        }

        return $this->_ob_cache = (string) $output;
    }
}