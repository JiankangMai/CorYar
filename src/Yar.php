<?php
namespace BromineMai\CorYar;
use BromineMai\CorYar\Packager\Json;
use BromineMai\CorYar\Packager\Msgpack;
use BromineMai\CorYar\Packager\Packager;
use BromineMai\CorYar\Exception\ExceptionHelper;
use BromineMai\CorYar\Packager\Php;
/**
 * Class Yar
 * @package BromineMai\CorYar
 */
class Yar{

    private static $supportCor;

    /**
     * 获取传输器
     * @param string $name
     * @return Packager
     * @throws \Yar_Client_Packager_Exception
     * @author Jiankang maijiankang@foxmail.com
     */
    public static function getPackager($name='default'){
        if('default'==$name){
            return new Php();
        }
        if(0===strpos($name,Msgpack::PACKAGER_NAME)){
            return new Msgpack();
        }
        if(0===strpos($name,Php::PACKAGER_NAME)){
            return new Php();
        }
        if(0===strpos($name,Json::PACKAGER_NAME)){
            return new Json();
        }
        ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_PACKAGER,'UNKONW PACKAGER'.$name);
    }



    /**
     * 判断是否是swoole协程中
     * @return bool
     * @author Jiankang maijiankang@foxmail.com
     */
    public static function isCoroutine(){
        return self::getCid()>0;
    }

    /**
     * 获取协程编号
     * @return bool|int -1主协程 false不支持协程 其他为进程内唯一的协程编号
     * @author Jiankang maijiankang@foxmail.com
     */
    public static function getCid(){
        if(!isset(self::$supportCor)){
            self::$supportCor=extension_loaded('swoole');
        }
        if(!self::$supportCor){
            return false;
        }
        return \Swoole\Coroutine::getCid();
    }
}