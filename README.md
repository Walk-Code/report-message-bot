# report-message

发送信息(日志等)到指定平台(企业微信等)

## 代码格式化(需自行安装 php-cs-fixer)

```bash  
./vendor/bin/php-cs-fixer fix ./src
```

## 测试

```bash  
./vendor/bin/phpunit ./tests
```

## ENV
### ENV config
```
see .env.example
```
### Field Description
| Filed | Decription |
| :--- | ----: |
| work_wechat_bot | 企业微信机器人配置 |
| bot_url | 企业微信机器人域名 |
| bot_key | 企业微信机器人key |
| mail | 邮箱配置 |
| type | 邮箱服务器类型 |
| host | 邮箱服务器地址 |
| is_auth | 授权 值：true/false |
| username | 用户名称 |
| passwrod | 用户密码 |
| tls | 加密方式 值：PHPMailer::ENCRYPTION_SMTPS/PHPMailer::ENCRYPTION_STARTTLS |
| prot | 端口 值：465 |

## Quickstart
[发送消息到企业微信机器人](./examples/example_work_wechat.php)
[发送消息到邮箱](./examples/example_email.php)

## TODO

- [x] 接入企业微信bot
- [x] redis 实现滑动窗口计数
- [x] sendHandle 增加邮件
- [ ] 单元测试
