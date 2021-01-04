## 项目说明

Laravel实战项目, 图书商城api, 本项目主要以Laravel在项目中的实战为核心, 简化了业务流程, 而更加注重Laravel的应用

使用DingoApi+JWT完成Api的开发, 以及授权认证等

其中更是涉及了Laravel的高级应用, 比如事件, 队列, 计划任务等

使用部分第三方Api, 比如阿里云云存储OSS, 阿里云云通信短信服务, 快递查询等

## 安装

进入项目中, 安装需要的依赖:

```
$ cd /var/www/shopApi
$ php composer install
```

> 如果提示 `composer` 内存不足: `COMPOSER_MEMORY_LIMI=-1 composer install`

创建 `.env`:

```
$ cp .env.example .env
```

生成应用秘钥:

```
$ php artisan key:generate
```

发布DingoApi配置:

```
$ php artisan vendor:publish --provider="Dingo\Api\Provider\LaravelServiceProvider"
```

发布JWT配置:

```
$ php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

发布CORS配置:

```
$ php artisan vendor:publish --tag="cors" 
```

发布权限相关配置:

```
$ php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
```

生成JWT TOKEN:

```
$ php artisan jwt:secret
```

修改 `.env` 中的数据库配置:

```
DB_DATABASE=laravel_shop_api
DB_USERNAME=username
DB_PASSWORD=password
```

配置好数据库之后, 执行数据迁移, 同时填充数据:

```
$ php artisan migrate --seed
```

> 不是必须, 根据需求是手动录入还是随机生成

## 配置

如果需要完整的体验项目, 您需要进行一些额外的配置

**邮箱配置**

只有配置邮箱, 才能使用邮件通知相关的功能

**支付配置**

配置支付宝配置, 即可使用支付宝支付

> 注意: 如果是本地测试, 可以使用支付宝沙箱支付, 微信不支持个人账号测试, 必须有企业资质

**阿里云OSS配置**

如果要使用文件存储相关的功能, 您可以配置阿里云OSS

**阿里云短信通信**

只有正确配置了阿里云通信, 才能使用短信发送功能, 当然, 您可以和阿里云OSS公用AK和SK, 但要注意阿里云子账户权限

## 队列和计划任务

依赖于队列和计划任务, 请务必配置好队列和计划任务, 推荐使用守护进程守护队列

## 优化

项目成功部署之后, 可以开启部分缓存, 提高访问速度, 如果是开发阶段, 不建议开启

开启路由缓存:

```
$ php artisan api:cache
```

开启配置文件缓存:

```
$ php artisan config:cache
```
