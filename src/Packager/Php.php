<?php
namespace BromineMai\CorYar\Packager;
class Php implements Packager {

    const PACKAGER_NAME='PHP';
    
    public function getName()
    {
        return self::PACKAGER_NAME;
    }
    
    public function pack($zval)
    {
        return serialize($zval);
    }

    public function unPack($zval)
    {
        return unserialize($zval);
    }
}