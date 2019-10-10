<?php
namespace BromineMai\CorYar\Body;
class Response  implements Body {

    public $id=null;
    public $status=null;
    public $out=null;
    public $retval=null;
    public $err=null;

    public function __construct($id=null, $status=null, $out=null, $retval=null, $err=null)
    {
        $this->id=$id;
        $this->status=$status;
        $this->out=$out;
        $this->retval=$retval;
        $this->err=$err;
    }
    
    public static  function fromZval($isroe){
        if(is_array($isroe)){
            $id=isset($isroe['i'])?$isroe['i']:null;;
            $status=isset($isroe['s'])?$isroe['s']:null;
            $out=isset($isroe['o'])?$isroe['o']:null;
            $retval=isset($isroe['r'])?$isroe['r']:null;
            $err=isset($isroe['e'])?$isroe['e']:null;
        }else{
            $id=isset($isroe->i)?$isroe->i:null;;
            $status=isset($isroe->s)?$isroe->s:null;
            $out=isset($isroe->o)?$isroe->o:null;
            $retval=isset($isroe->r)?$isroe->r:null;
            $err=isset($isroe->e)?$isroe->e:null;
        }
        return new static($id,$status,$out,$retval,$err); 
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