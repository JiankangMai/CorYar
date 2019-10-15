# Yar RPC框架PHP协程版本

提供不依赖拓展的客户端和服务端，含Yar服务协程化改造首选。

主要用于解决两个问题：

1. 协程环境下，Yar RPC调用阻塞协程，`STEAM HOOK`可用前的协程解决方案
2. Yar服务端 无法在Swoole Http Server中部署的问题。

项目API兼容原生拓展，可以轻松完成迁移。

## 安装
composer一键安装  ` composer require bromine-mai/cor-yar`


然后在项目bootstrap中文件中  加载composer自带的psr类加载器即可
```
require_once (ROOT_PATH.'/vendor/autoload.php');
```
CorYar非常轻量，没有对Yar拓展的依赖，开箱即用。

## Client使用

```
<?php 
//使用use导入新类方式替换客户端
use \BromineMai\CorYar\Client\Client as Yar_Client;
$client = new Yar_Client("http://host/api/");
$result = $client->api("parameter);

```

兼容原生Yar。未安装Yar的情况下仍能直接使用原生的Exception,配置常量等，使用方式和API参考官方文档即可。

底层会根据上下文自动使用同步，或者协程方式进行RPC调用，无需过多关注细节。
基本上替换Yar客户端就是一行`use \BromineMai\CorYar\Client\Client as Yar_Client;`的事

## Server使用
由于原生Yar Server依赖`SG(request_info)`等全局变量，无法在Swoole Server环境下运行。

为了部署了Yar的服务迁移到Swoole上，同时提供Swoole版本的Yar_Server用于Swoole服务端。

```
use \BromineMai\CorYar\Server\SwooleServer as Yar_Server;
$http = new Server("127.0.0.1", 9501);
$http->on('request', function ($request, $response) {
    $server=new Yar_Server(new XXXApi());
	$server->setIoHandler($request,$response);//设置IO句柄
	$server->handle(); 
});
$http->start();
```

`SwooleServer`与原生Server相比，需要在调用`handle()`前，额外的将swoole的`$request, $response`对象通过`setIoHandler`传给`$server`，其他使用方法和原生Yar一致。


如果你使用的Swoole框架屏蔽了底层细节，令你无法获取`\Swoole\Http\Request`,`\Swoole\Http\Response`变量，你也可以通过设置输入输出的回调方法代替`setIoHandler()`
```
        $server->setInputHandle(function()use($xxFrameworkRequest){
            return $xxFrameworkRequest->getRawContent();
        });
        $server->setOutputHandle(function($result){
            echo $result;
        });
```




CorYar也提供了更基础的`\BromineMai\CorYar\Server\Server`在非Swoole环境下，作为Yar原生服务端的替代。


## 依赖
PHP5.4+

CURL拓展 (非协程上下文)

Swoole4.0+ (协程上下文)

msgpack(如果指定了msgpack传输器)


## 其他
任何问题，随时提PR