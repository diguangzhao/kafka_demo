<?php

namespace Dea;

class Util {
	public static function pathAndArgs(array $argv, $guessCase = false)
    {
        $path = '';
        $candidates = [];

        while (count($argv) > 0) {
            $arg = array_shift($argv);
            if ($guessCase) {
                $arg = implode('', array_map('ucwords', explode('_', strtr($arg, ['-' => '_']))));
            }
            if (!preg_match('|^[a-z][a-z0-9-_]+$|i', $arg)) {
                break;
            }
            $path .= '/'.$arg;
            $candidates[$path] = $argv;
        }

        return $candidates;
    }
}