<?php
namespace BromineMai\CorYar\Transport;
use BromineMai\CorYar\Body\Response;
use BromineMai\CorYar\Exception\ExceptionHelper;
use BromineMai\CorYar\Protocol\Protocol;
use \Swoole\Coroutine\Http\Client;

/**
 * swoole协程传输器
 * Class CorHttp
 * @package BromineMai\CorYar\Transport
 */
class CorHttp implements Transport {
    private $addRess;
    private $options;
    /**
     * @var  Client $objHttp
     */
    private $objHttp;
    private $url;
    private $domain;
    private $isHttps;
    private $port;
    
    private static function parseAddress($address){
        $par='|^(https?\:\/\/)?([^\:\/]+)(\:(\d+))?(.*)$|';
        $rst=preg_match($par,$address,$match);
        if($rst){
            return [
                'protocol' => $match[1],
                'host'=>$match[2],
                'port'=>$match[4],
                'url'=>$match[5],
            ];
        }else{
            ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_REQUEST,'Addredss parse error:'.$address);
        }
    }


    /**
     * @inheritdoc
     */
    public function open($address, $options)
    {
        $this->addRess=$address;
        $this->options=$options;
        $addressInfo=$this::parseAddress($address);
        $this->isHttps=(strtolower($addressInfo['protocol'])=='https://');
        $this->domain=$domain=$addressInfo['host'];
        $this->port=empty($addressInfo['port'])?80:$addressInfo['port'];
        $this->url=empty($addressInfo['url'])?'':$addressInfo['url'];
        $this->objHttp= new Client($this->domain, $this->port,$this->isHttps);
    }

    /**
     * @inheritdoc
     */
    public function exec($request){}

    /**
     * @inheritdoc
     */
    public function close($request)
    {
        $cli=$this->objHttp;
        $statusCode=$cli->getStatusCode();
        if(200!=$statusCode){
            $errCode=$cli->errCode;
            $errMsg=$cli->errMsg;
            ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_TRANSPORT,'cor_http_post_error:'.json_encode(['host'=>$this->domain,'port'=>$this->port,'statusCode'=>$statusCode,'strUrl'=>$this->url,'errCode'=>$errCode,'errMsg'=>$errMsg]));
        }
        $p=new Protocol;
        $ret=$p->unRender($cli->body );
        $cli->close();
        return  new Response($ret );
    }


    /**
     * @inheritdoc
     */
    public function send($request){
        $cli=$this->objHttp;
        $cli->setHeaders([
            'Host' => "{$this->domain}",
        ]);
        $arrConf=[];
        if(isset($options['connTimeout'])){
            $arrConf['timeout']=$options['connTimeout'];
        };
        if(isset($options['timeout'])){
            $arrConf['timeout']=$options['timeout'];
        };
        $this->objHttp->set($arrConf);
        $p=new Protocol;
        $string=$p->render($request);
        $cli->setData($string);
        $cli->setMethod('POST');
        $cli->execute($this->url);
    }



}
