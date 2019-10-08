<?php
namespace BromineMai\CorYar\Exception;
use BromineMai\CorYar\Body\Response;

require_once __DIR__.'/CorExceptionInit.php';
/**
 * 异常Helper
 * Class ExceptionHelper
 * @package BromineMai\CorYar\Exception
 */
class ExceptionHelper {

    const  YAR_ERR_OKEY =     		0x0;
    const  YAR_ERR_PACKAGER = 		0x1;
    const  YAR_ERR_PROTOCOL  =		0x2;
    const  YAR_ERR_REQUEST   =		0x4;
    const  YAR_ERR_OUTPUT    =		0x8;
    const  YAR_ERR_TRANSPORT =		0x10;
    const  YAR_ERR_FORBIDDEN =		0x20;
    const  YAR_ERR_EXCEPTION =		0x40;
    const  YAR_ERR_EMPTY_RESPONSE =	0x80;
    const  COR_YAR_ERR  =          0x20;//COR YAR特有错误      

    /**
     * @param string $code
     * @param string $msg
     * @throws \Exception
     * @author Jiankang maijiankang@foxmail.com
     */
    public static function throwYarException($code,$msg,$previous=null) {
        switch ($code) {
            case self::YAR_ERR_PACKAGER:
                throw new \Yar_Client_Packager_Exception($msg,$code,$previous);
            case self::YAR_ERR_PROTOCOL:
                throw new \Yar_Client_Protocol_Exception($msg,$code,$previous);
            case self::YAR_ERR_TRANSPORT:
                throw new \Yar_Client_Transport_Exception($msg,$code,$previous);
            case self::YAR_ERR_REQUEST:
            case self::YAR_ERR_EXCEPTION:
                throw new \Yar_Server_Exception($msg,$code,$previous);
            default:
                throw new \Yar_Client_Exception($msg,$code,$previous);
                break;
        }
    }

    /**
     * 根据服务端响应抛出异常
     * @param Response $response
     * @throws \Yar_Server_Exception
     * @author Jiankang maijiankang@foxmail.com
     */
    public static function throwYarExceptionFromServer(Response $response) {
        $responseErr=$response->err;
        //if(ExceptionHelper::YAR_ERR_EXCEPTION ==$response->status){
        if(is_array($responseErr)){
            $reflectCls = new \ReflectionClass ('Yar_Server_Exception');
            $exp=new \Yar_Server_Exception();
            //$exp= $reflectCls ->newInstanceWithoutConstructor();
            $arr=[
                'message'=>$responseErr['message'],
                'code'=>$responseErr['code'],
                'file'=>$responseErr['file'],
                'line'=>$responseErr['line'],
                '_type'=>$responseErr['_type'],
                
            ];
            foreach($arr as $key => $val) {
                $pro = $reflectCls->getProperty($key);
                if ($pro && ($pro->isPrivate() || $pro->isProtected())) {
                    $pro->setAccessible(true);
                    $pro->setValue($exp, $val);
                    $pro->setAccessible(false);
                } else {
                    $exp->$key = $val;
                }
            }
            throw $exp;
        }else{
            ExceptionHelper::throwYarException($response->status,'server error:'.$response->err);
        }
        

    }
}