<?php
namespace BromineMai\CorYar\Packager;
class Json implements Packager {
    
    const PACKAGER_NAME='JSON';
    
    public function getName()
    {
        return self::PACKAGER_NAME;
    }


    public function pack($zval)
    {
        return json_encode($zval);
    }

    public function unPack($zval)
    {
        return json_decode($zval,true);
    }
}