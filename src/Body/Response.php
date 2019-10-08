<?php
namespace BromineMai\CorYar\Body;
class Response  implements Body {

    public $id=null;
    public $status=null;
    public $out=null;
    public $retval=null;
    public $err=null;

    public function __construct($isroe)
    {
        if(is_array($isroe)){
            $this->id=isset($isroe['i'])?$isroe['i']:null;;
            $this->status=isset($isroe['s'])?$isroe['s']:null;
            $this->out=isset($isroe['o'])?$isroe['o']:null;
            $this->retval=isset($isroe['r'])?$isroe['r']:null;
            $this->err=isset($isroe['e'])?$isroe['e']:null;
        }else{
            $this->id=isset($isroe->i)?$isroe->i:null;;
            $this->status=isset($isroe->s)?$isroe->s:null;
            $this->out=isset($isroe->o)?$isroe->o:null;
            $this->retval=isset($isroe->r)?$isroe->r:null;
            $this->err=isset($isroe->e)?$isroe->e:null;    
        }
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getZval(){
        return [
            'i'=>$this->id,
            's'=>$this->status,
            'o'=>$this->out,
            'r'=>$this->retval,
            'e'=>$this->err,
        ];
    }

}