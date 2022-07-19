# report-message

发送信息(日志等)到指定平台(企业微信等)

## 代码格式化(需自行安装 php-cs-fixer)

```bash  
php-cs-fixer fix .
```

## 测试

```bash  
vendor/bin/phpunit
```


## Quickstart

```php
<?php
use Jianzhi\reportMessage\enum\LogLevel;
use Jianzhi\reportMessage\ReportMessage;

ReportMessage::setRedis(tp_redis());
ReportMessage::send(LogLevel::ERROR(), "发送的错误信息");
```

## TODO

- [] redis 实现滑动窗口计数
- [] sendHandle 增加邮件,短信等
