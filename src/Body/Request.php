<?php
namespace BromineMai\CorYar\Body;
class Request implements Body{
    
    public $id=null;
    public $method=null;
    public $arrParam=null;
    public $option=null;
    
    public function __construct($method,$arrParam=[],$option=[],$id=null)
    {
        $this->id=isset($id)?$id:mt_rand();
        $this->method=$method;
        $this->arrParam=$arrParam;
        $this->option=$option;

    }

    /**
     * @param $zval
     * @return Request
     * @author Jiankang maijiankang@foxmail.com
     */
    public static function fromZval($zval){
        if(is_array($zval)){
            return new static($zval['m'],$zval['p'],[],$zval['i']);    
        }else{
            return new static($zval->m,$zval->p,[],$zval->i);
        }
        
    }
    public function getId(){
        return $this->id;
    }
    
    public function getZval(){
        return [
            'i'=>$this->id,
            'm'=>$this->method,
            'p'=>$this->arrParam,
            //'o'=>$this->option,
        ];
    }

}