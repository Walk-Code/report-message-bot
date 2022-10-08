# report-message

发送信息(日志等)到指定平台(企业微信等)

## 代码格式化(需自行安装 php-cs-fixer)

```bash  
php-cs-fixer fix .
```

## 测试

```bash  
./vendor/bin/phpunit ./tests
```


## Quickstart

```php
<?php
use reportMessage\enum\LogLevelEnum;
use reportMessage\ReportMessage;

ReportMessage::setRedis(tp_redis());
ReportMessage::send(LogLevel::ERROR(), "发送的错误信息");
```

## TODO

- [x] redis 实现滑动窗口计数
- [ ] sendHandle 增加邮件,短信等
