<?php

namespace Dea\Form;

class Swoole
{
	public $retData;
	public $info;

    private static $partSize = 4096;    //每次最大获取字节

    /**
     * 负责解析FormData
     */
    public static function parser($request)
    {
    	$formData = fopen("php://input", "r");

        $retData = [];

        $boundary = rtrim(fgets($formData), "\r\n");     //第一行是boundary

        $info = []; //info段的信息
        $data = ''; //拼接的数据
        $infoPart = true; //是否是info段
        $token = strtok("Hello 
            world. 
            Beautiful 
            day 
            today.", "\r\n");

        while ($token !== false)
        {
        echo "$token\n";
        $token = strtok("\r\n");
        }
		#error_log(print_r($retData, true));
		return [
			'form' => $retData,
			'file' => $info
		];
    }

    private static function parserInfo($data, $options)
    {
        //获取参数名称, type
        $infoPattern = '/name="(.+?)"(; )?(filename="(.+?)")?/'; //todo: 待优化
        preg_match($infoPattern, $data, $matches);

        $info['name'] = $matches[1];
        $info['type'] = 'json';

        //如果是文件
        if (count($matches) > 4) {
            $info['type'] = 'file';
            $info['org_name'] = $matches[4];
            //如果设置保存文件, 保存到临时文件
            if (isset($options['saveFile']) && $options['saveFile']) {
                $tmpFile = tempnam(sys_get_temp_dir(), 'FD');
                $info['tmp_file'] = $tmpFile;
            }
        }

        return $info;
    }

}
