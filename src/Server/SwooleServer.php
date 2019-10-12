<?php
namespace BromineMai\CorYar\Server;
use BromineMai\CorYar\Exception\ExceptionHelper;
use Swoole\Http\Request;
use Swoole\Http\Response;

class SwooleServer extends  Server{

    /**
     * @var Request
     */
    private $request;
    /**
     * @var Response
     */
    private $response;

    protected $inputHandle;
    protected $outputHandle;


    /**
     * 设置获取输入句柄(回调函数方式)
     * @param Callable $inputHandle 无参，返回值为http body 
     * @author Jiankang maijiankang@foxmail.com
     */
    public function setInputHandle($inputHandle){
        if(is_callable($inputHandle)){
            $this->inputHandle=$inputHandle;
        }else{
            ExceptionHelper::throwYarException(ExceptionHelper::COR_YAR_ERR ,'$inputHandle not valid callable');
        }

    }

    /**
     * 设置获取输出句柄(回调函数方式)
     * @param Callable $outputHandle 参数为要打印的内容
     * @param 
     * @author Jiankang maijiankang@foxmail.com
     */
    public function setOutputHandle($outputHandle){
        if(is_callable($outputHandle)) {
            $this->outputHandle = $outputHandle;
        }else{
            ExceptionHelper::throwYarException(ExceptionHelper::COR_YAR_ERR ,'$outputHandle not valid callable');
        }
    }

    /**
     * 设置swoole的基础输入句柄(Swoole\Http\Request)方式
     * setIoHandler
     * @param Request $request
     * @param Response $response
     * @author Jiankang maijiankang@foxmail.com
     */
    public function setIoHandler($request,$response){
        $this->request=$request;
        $this->response=$response;
    }


    protected  function headerIsSend(){
        return false;
    }

    protected  function getInput(){
        if(null!==$this->inputHandle){
            $f=$this->inputHandle;
            return $f();
        }else if(null !==$this->request){
            return $this->request->rawContent();
        }else{
            ExceptionHelper::throwYarException(ExceptionHelper::COR_YAR_ERR,'must call setOutputHandle() or setIoHandler');
        }
    }

    protected  function output($string){
        if(null!==$this->outputHandle){
            $f=$this->outputHandle;
            $f($string);
        }else if(null !==$this->response){
            $this->response->end($string);
        }else{
            ExceptionHelper::throwYarException(ExceptionHelper::COR_YAR_ERR,'must call setOutputHandle() or setIoHandler');
        }
    }

}