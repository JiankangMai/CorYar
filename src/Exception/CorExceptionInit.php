<?php
if(!class_exists('Yar_Client_Exception')){
    class Yar_Client_Exception extends Exception{}
}
if(!class_exists('Yar_Client_Protocol_Exception')){
    class Yar_Client_Protocol_Exception extends Exception{}
}
if(!class_exists('Yar_Client_Packager_Exception')){
    class Yar_Client_Packager_Exception extends Exception{}
}
if(!class_exists('Yar_Client_Transport_Exception')){
    class Yar_Client_Transport_Exception extends Exception{}
}
if(!class_exists('Yar_Server_Exception')){
    class Yar_Server_Exception extends Exception{
        protected $_type;
        final public function getType (){
            return $this->_type;     
        }
    }
}
if(!class_exists('Yar_Server_Request_Exception')){
    class Yar_Server_Request_Exception extends Exception{}
}
if(!class_exists('Yar_Server_Protocol_Exception')){
    class Yar_Server_Protocol_Exception extends Exception{}
}
if(!class_exists('Yar_Server_Packager_Exception')){
    class Yar_Server_Packager_Exception extends Exception{}
}
if(!class_exists('Yar_Server_Output_Exception')){
    class Yar_Server_Output_Exception extends Exception{}
}