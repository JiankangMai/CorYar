<?php
namespace BromineMai\CorYar\Body;
/**
 * Interface Body
 * @package BromineMai\CorYar\Body
 */
Interface Body {
    public function getId();
    public function getZval();

    /**
     * @param array|object $zval
     * @return static
     * @author Jiankang maijiankang@foxmail.com
     */
    public static function fromZval($zval);

}