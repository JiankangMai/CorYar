<?php
namespace BromineMai\CorYar\Transport;
use BromineMai\CorYar\Body\Response;
use BromineMai\CorYar\Exception\ExceptionHelper;
use BromineMai\CorYar\Protocol\Protocol;
use BromineMai\CorYar\Body\Request;
/**
 * curl同步传输器
 * Class Curl
 * @package BromineMai\CorYar\Transport
 */
class Curl implements Transport {
    private $addRess;
    private $options;
    private $curlOptions;
    private $curlInfo=[];
    private $strHead='';
    private $strBody='';


    public function __construct(){
        $this->curlOptions = array(
            'conn_timeout' => 1000,
            'timeout' => 3000,
            'user_agent' => '',
            'referer' => '',
            'encoding' => '',
        );
    }

    
    public  function onResponseHeader($objCurl, $strHead){
        $this->strHead.= $strHead;
        return strlen($strHead);
    }

    public  function onResponseData($objCurl, $strBody){
        $this->strBody.= $strBody;
        return strlen($strBody);
    }


    private function post($strUrl, $arrParams, $arrCookies=array(), $bolUpload=false,$arrHeader=array(),$intPort=0){

        $this->curlInfo = array();
        $this->strHead = "";
        $this->strBody = "";
        $objCurl = curl_init();
        $arrOptions = array(
            CURLOPT_URL => $strUrl,
            CURLOPT_CONNECTTIMEOUT_MS => $this->curlOptions['conn_timeout'],
            CURLOPT_TIMEOUT_MS => $this->curlOptions['timeout'],
            CURLOPT_USERAGENT => $this->curlOptions['user_agent'],
            CURLOPT_REFERER => $this->curlOptions['referer'],
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_ENCODING => $this->curlOptions['encoding'],
            CURLOPT_WRITEFUNCTION => [$this,'onResponseData'],
            CURLOPT_HEADERFUNCTION => [$this,'onResponseHeader'],
        );
        if($intPort){
            $arrOptions[CURLOPT_PORT]=$intPort;
        }
        //curl_setopt($objCurl, CURLOPT_PROXY, '172.16.255.230:8888');//TODO DEL
        if (is_array($arrCookies) && count($arrCookies) > 0){
            $strCookie = "";
            foreach($arrCookies as $key => $value){
                $strCookie .= "{$key}={$value}; ";
            }
            $arrOptions[CURLOPT_COOKIE] = $strCookie;
        }
        if(!empty($arrHeader)){
            curl_setopt($objCurl, CURLOPT_HTTPHEADER, $arrHeader);
        }

        curl_setopt_array($objCurl, $arrOptions);

        $post = $bolUpload ? $arrParams : http_build_query($arrParams);
        curl_setopt($objCurl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($objCurl, CURLINFO_HEADER_OUT, true); //允许查看请求的header
        curl_exec($objCurl);

        $intErrno = curl_errno($objCurl);
        $strErrmsg = curl_error($objCurl);
        $this->curlInfo = curl_getinfo($objCurl);

        curl_close($objCurl);

        if(CURLE_OK !== $this->curlInfo['http_code']){
            return $this->strBody;
        }else{
            ExceptionHelper::throwYarException(ExceptionHelper::YAR_ERR_TRANSPORT,'curl_post_error:'.json_encode(['strUrl'=>$strUrl, 'intErrno'=>$intErrno, 'strErrmsg'=>$strErrmsg]));
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function open($address, $options)
    {
        $this->addRess=$address;
        $this->options=$options;
        
        if (!empty($arrOptions) && is_array($arrOptions)){
            $this->curlOptions = $arrOptions + $this->curlOptions;
        }
        
        if(isset($options['connTimeout'])){
            $this->curlOptions['conn_timeout']=$options['connTimeout'];
        }
        if(isset($options['timeout'])){
            $this->curlOptions['timeout']=$options['timeout'];
        }
    }

    /**
     * @inheritdoc
     */
    public function exec($request)
    {
    }

    /**
     * @inheritdoc
     */
    public function close($request)
    {
        $p=new Protocol;
        $string=$p->render($request);
        $ret = $this->post($this->addRess, $string, null, true);
        $ret=$p->unRender($ret);
        return  new Response($ret );
    }

    /**
     * @inheritdoc
     */
    public function send($request){}



}
