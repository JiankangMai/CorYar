<?php
namespace BromineMai\CorYar\Server;
use BromineMai\CorYar\Body\Request;
use BromineMai\CorYar\Body\Response;
use BromineMai\CorYar\Exception\ExceptionHelper;
use BromineMai\CorYar\Protocol\Protocol;
use Swoole\Exception;

class Server{

    protected $_executor;
    public function __construct($obj )
    {
        if(!is_object($obj)){
            return;
        }
        $this->_executor=$obj ;
    }

    
    
    public function handle()
    {
        ob_start();
        $result=false;
        if ($this->headerIsSend()) {
            trigger_error ( 'have send headers',E_WARNING);
        }else if(!is_object($this->_executor)){
            trigger_error('executor is not a valid object', E_WARNING);
        }else{
            return $this->serverHandle();
        }
        return $result;
    }


    protected  function headerIsSend(){
        return headers_sent();
    }

    protected  function getInput(){
        return file_get_contents('php://input');
    }

    protected  function output($string){
        echo $string;
    }
    
    private function serverHandle(){
        $o='';
        $result=false;
        try{
            $p=new Protocol();
            $retval=null;
            $imp=$p->unRender($this->getInput());
            $request=Request::fromZval($imp);
            $retval=call_user_func_array([$this->_executor,$request->method],$request->arrParam);
            $o = ob_get_clean();
            $response=new Response($request->id,ExceptionHelper::YAR_ERR_OKEY,$o,$retval);
            //ob_end_clean();
            $result =true;
        }catch (Exception $e){
            $response=new Response(isset($request)?$request->id:null,
                ExceptionHelper::YAR_ERR_EXCEPTION,$o,
                null,[
                    'message'=>$e->getMessage(),
                    'code'=>$e->getCode(),
                    'file'=>$e->getFile(),
                    'line'=>$e->getLine(),
                    '_type'=>get_class($e),
                ]);
        }
        $this->output($p->render($response));
        return $result;
    }

}