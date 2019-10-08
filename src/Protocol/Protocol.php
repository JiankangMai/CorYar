<?php
namespace BromineMai\CorYar\Protocol;
use BromineMai\CorYar\Packager\Packager;
use BromineMai\CorYar\Exception\ExceptionHelper;
use BromineMai\CorYar\Yar;
/**
 * Class Protocol
 * @package BromineMai\CorYar\Protocol
 */
class Protocol  {

    const VERSION=0;
    const MAGIC_CODE=0x80DFEC60;
    const RESERVED=0;

    /**
     * @param \BromineMai\CorYar\Body\Body $body 
     * @param Packager $packer $packer
     * @param string $provider
     * @param string $token
     * @return string 
     * @author Jiankang maijiankang@foxmail.com
     */
    public function render($body, $packer=null,$provider='CorYar', $token=''){
        if(null===$packer ){
            if(isset($body->option) && isset($body->option['packager'])){
                $packer=$body->option['packager'];
                $packer=Yar::getPackager($packer);
            }else{
                $packer=Yar::getPackager();
            }
        }
        $provider=  sprintf('%- 32s', substr($provider."\0",0,32));
        $token=     sprintf('%- 32s', substr($token."\0",0,32));
        $strPackHead=sprintf("%- 8s", substr($packer->getName()."\0",0,8));
        $strPack=$packer->pack($body->getZval());
        $result=  
            pack('N',$body->getId()).
            pack('n',self::VERSION).
            pack('N',self::MAGIC_CODE).
            pack('N',self::RESERVED).
            $provider.
            $token.
            pack('N',strlen($strPackHead.$strPack)).
            $strPackHead.
            $strPack;
        return $result;
    }


    /**
     * @param $string
     * @return array
     * @author Jiankang maijiankang@foxmail.com
     */
    public function unRender($string){
        if(strlen($string)<90){
            ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_PROTOCOL ,'response content too short for protocol');
        }
        $id=unpack('N',substr($string,0,4))[1];
        $ver=unpack('n',substr($string,4,2))[1];
        if($ver>self::VERSION){
            ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_PROTOCOL ,'unknow version');
        }
        $magic=unpack('N',substr($string,6,4))[1];
        if(self::MAGIC_CODE!=$magic){
            ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_PROTOCOL ,'invalid magic num');
        }
        $reserved=unpack('N',substr($string,10,4))[1];
        $provide=substr($string,14,32);
        $token=substr($string,46,32);
        $len=unpack('N',substr($string,78,4))[1];
        $packager=Yar::getPackager(substr($string,82,8));
        $strContent=substr($string,90,$len);
        $isore=$packager->unpack($strContent);
        return $isore;
    }
    
    
}