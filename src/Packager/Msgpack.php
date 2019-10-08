<?php
namespace BromineMai\CorYar\Packager;
class Msgpack implements Packager {

    const PACKAGER_NAME='MSGPACK';
    
    public function getName()
    {
        return self::PACKAGER_NAME;
    }
    
    public function pack($zval)
    {
        return msgpack_pack($zval);
    }

    public function unPack($zval)
    {
        return msgpack_unpack($zval);
    }
}