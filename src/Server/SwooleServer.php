<?php
namespace BromineMai\CorYar\Server;
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
    
    /**
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
    
    protected function getInput(){
        return $this->request->rawContent();
    }
    
    protected function output($string){
        echo $this->response->end($string);
    }
    


}