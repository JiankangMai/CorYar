<?php
namespace BromineMai\CorYar\Body;
class Request implements Body{
    
    public $id=null;
    public $method=null;
    public $arrParam=null;
    public $option=null;
    
    public function __construct($method,$arrParam=[],$option=[])
    {
        $this->id=mt_rand();
        $this->method=$method;
        $this->arrParam=$arrParam;
        $this->option=$option;

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