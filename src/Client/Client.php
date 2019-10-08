<?php
namespace BromineMai\CorYar\Client;
use BromineMai\CorYar\Body\Request;
use BromineMai\CorYar\Body\Response;
use BromineMai\CorYar\Exception\ExceptionHelper;
use BromineMai\CorYar\Transport\CorHttp;
use BromineMai\CorYar\Transport\Curl;
use BromineMai\CorYar\Yar;

if(!defined('YAR_OPT_PACKAGER')){
    define('YAR_OPT_PACKAGER',1<<0);
}
if(!defined('YAR_OPT_PERSISTENT')){
    define('YAR_OPT_PERSISTENT',1<<1);
}
if(!defined('YAR_OPT_TIMEOUT')){
    define('YAR_OPT_TIMEOUT',1<<2);
}
if(!defined('YAR_OPT_CONNECT_TIMEOUT')){
    define('YAR_OPT_CONNECT_TIMEOUT',1<<3);
}

/**
 * @link 
 * Class Client
 * @package BromineMai\CorYar\Client
 */
class Client{
    
    const YAR_OPT_PACKAGER=YAR_OPT_PACKAGER;
    const YAR_OPT_PERSISTENT=YAR_OPT_PERSISTENT;
    const YAR_OPT_TIMEOUT=YAR_OPT_TIMEOUT;
    const YAR_OPT_CONNECT_TIMEOUT=YAR_OPT_CONNECT_TIMEOUT;
    
    private $address;
    public $packager;
    public $timeout;
    public $connTimeout;
    
    public function __construct($address)
    {
        $this->address=$address;
    }

    public function setOpt($name,$value){
        switch ($name){
            case YAR_OPT_PACKAGER:
                $this->packager=$value;break;
            case YAR_OPT_PERSISTENT:
                ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_EXCEPTION,'unsport opt YAR_OPT_PERSISTENT');break;
            case YAR_OPT_TIMEOUT:
                $this->timeout=$value;break;
            case YAR_OPT_CONNECT_TIMEOUT:
                $this->connTimeout=$value;break;
            default:
                ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_EXCEPTION,'unsport opt name'.$name);
        }
    }

    public function __call($name, $arguments)
    {
        $option=[];
        if(null!==$this->packager)
            $option['packager']=$this->packager;
        if(null!==$this->timeout)
            $option['timeout']=$this->timeout;
        if(null!==$this->connTimeout) 
            $option['connTimeout']=$this->connTimeout;
        $resutst=(new Request($name,$arguments,$option));
        try{
            if($icCor=Yar::isCoroutine()){
                $transport=(new CorHttp());
            }else{
                $transport=(new Curl());
            }
            
            $transport->open($this->address,$option);
            $transport->send($resutst);
            $transport->exec($resutst);
            
            $response =$transport->close($resutst);
        }catch (\Exception $e){
            ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_EXCEPTION,'err:'.$e->getMessage(),$e);
        }
        
        if($response->status!=ExceptionHelper::YAR_ERR_OKEY){
            if(!empty($response->err)){
                ExceptionHelper::throwYarExceptionFromServer($response);
            }else{
                ExceptionHelper::throwYarException($response->status,'rpc error.');
            }
        }else{
            if(!empty($response->out)){
                echo $response->out;
            }
            return $response->retval;
        }
    }
    
}