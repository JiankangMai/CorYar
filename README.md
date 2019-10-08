# Yar RPC框架PHP协程客户端
用于解决Swoole 协程环境下，Yar客户端调用阻塞的问题，`STEAM HOOK`可用前的协程解决方案。
## 安装

composer安装  ` composer require bromine-mai/cor-yar`


## 使用方式
```
//原生Yar客户端代码
$client = new Yar_Client("http://host/api/");
$result = $client->api("parameter);
```

```
//使用use导入新类方式替换客户端
use \BromineMai\CorYar\Client\Client as Yar_Client;
$client = new Yar_Client("http://host/api/");
//直接替换类名方式
//$client = new \BromineMai\CorYar\Client\Client("http://host/api/");

$result = $client->api("parameter);

```

非常轻量，没有对Yar拓展的依赖，开箱即用。

兼容原生Yar。未安装Yar的情况下仍能直接使用原生的Exception,配置常量等，使用方式参考官方文档即可。

底层会根据上下文自动使用同步，或者协程方式进行RPC调用，无需过多关注细节。




## 依赖
PHP5.4+

CURL拓展 (非协程上下文)

Swoole4.0+ (协程上下文)

无对Yar拓展的依赖，未加载yar.so的环境也可以使用

## 其他
任何问题，随时提PR