<?php

namespace Dea;

class URI {

	protected static $baseurl;

    public static function setup() {
        $host = $_SERVER['HTTP_HOST'];
        $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?: ($_SERVER['HTTPS'] ? 'https' : 'http');
        $dir = dirname($_SERVER['SCRIPT_NAME']);
        if (substr($dir, -1) != '/') {
            $dir .= '/';
        }
        self::$baseurl = $scheme.'://'.$host.$dir;

    }

    public static function base() {
    	if (!isset(self::$baseurl)) {
    		self::setup();
    	}
    	return self::$baseurl;

    }

    public static function url($url = null, $query = null) {

        if (!isset(self::$baseurl)) {
            self::setup();
        }

        $uri = parse_url($url);

        if ($query) {
            if ($uri['query']) {
                if (is_string($query)) {
                    parse_str($query, $query);
                }
                parse_str($uri['query'], $old_query);
                $uri['query'] = http_build_query(array_merge($old_query, $query));
            } else {
                $uri['query'] = is_string($query) ? $query : http_build_query($query);
            }
        }

        if ($uri['query']) {
            $url .= '?'.$uri['query'];
        }

        return self::$baseurl . $url;
    }

}
