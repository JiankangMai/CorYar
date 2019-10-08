<?php
namespace BromineMai\CorYar\Packager;
Interface Packager
{
    public function getName();
    public function pack($zval);
    public function unPack($zval);
}