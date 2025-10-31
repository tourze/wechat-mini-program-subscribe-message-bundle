# 微信小程序订阅消息Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![PHP Version Require](https://img.shields.io/packagist/php-v/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![License](https://img.shields.io/packagist/l/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/php-monorepo/ci.yml?branch=master&style=flat-square)](
https://github.com/tourze/php-monorepo/actions)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze/php-monorepo.svg?style=flat-square)](
https://codecov.io/gh/tourze/php-monorepo)

一个用于管理微信小程序订阅消息的 Symfony Bundle。该包提供了处理订阅消息分类、模板和消息发送的
全面功能，并配有直观的管理界面。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [配置](#配置)
- [快速开始](#快速开始)
- [高级用法](#高级用法)
  - [表达式语言函数](#表达式语言函数)
  - [使用模板](#使用模板)
  - [事件处理](#事件处理)
- [命令](#命令)
  - [wechat:mini-program:sync-subscribe-categories](#wechatmini-programsync-subscribe-categories)
  - [wechat-mini-program:sync-subscribe-template](#wechat-mini-programsync-subscribe-template)
- [管理界面](#管理界面)
- [安全](#安全)
- [贡献](#贡献)
- [依赖项](#依赖项)
- [许可证](#许可证)

## 功能特性

- **订阅分类管理**：自动从微信API同步和管理订阅消息分类
- **模板管理**：同步和管理带参数的订阅消息模板
- **消息发送**：支持表达式语言的用户订阅消息发送
- **管理界面**：完整的 EasyAdmin CRUD 控制器进行数据管理
- **定时任务集成**：可配置调度的自动同步
- **事件系统**：事件驱动的消息处理和日志架构
- **表达式语言**：支持表达式语言的动态消息内容

## 安装

通过 Composer 安装Bundle：

```bash
composer require tourze/wechat-mini-program-subscribe-message-bundle
```

将Bundle添加到您的 `config/bundles.php`：

```php
<?php
return [
    // ... 其他包
    WechatMiniProgramSubscribeMessageBundle\WechatMiniProgramSubscribeMessageBundle::class => ['all' => true],
];
```

## 配置

该Bundle与微信小程序 Bundle 配置集成。请确保您已配置了微信小程序账户凭证：

```yaml
# config/packages/wechat_mini_program.yaml
wechat_mini_program:
    accounts:
        default:
            app_id: '%env(WECHAT_MINI_PROGRAM_APP_ID)%'
            secret: '%env(WECHAT_MINI_PROGRAM_SECRET)%'
```

可选地，您可以配置该Bundle的特定设置：

```yaml
# config/packages/wechat_mini_program_subscribe_message.yaml
wechat_mini_program_subscribe_message:
    # 启用/禁用自动模板同步
    auto_sync: true
    
    # 消息发送的默认设置
    default_settings:
        page: 'pages/index'
        miniprogram_state: 'formal'  # formal, trial, developer
```

## 快速开始

1. 在您的微信小程序Bundle配置中配置微信小程序账户凭证。

2. 运行数据库迁移以创建所需的表：

```bash
php bin/console doctrine:migrations:migrate
```

3. 从微信API同步订阅分类和模板：

```bash
# 同步订阅消息分类
php bin/console wechat:mini-program:sync-subscribe-categories

# 同步订阅消息模板
php bin/console wechat-mini-program:sync-subscribe-template
```

4. 发送订阅消息：

```php
use WechatMiniProgramSubscribeMessageBundle\Request\SendSubscribeMessageRequest;
use WechatMiniProgramBundle\Service\Client;

$request = new SendSubscribeMessageRequest();
$request->setToUser('user_openid');
$request->setTemplateId('your_template_id');
$request->setPage('pages/index');
$request->setData([
    'thing1' => ['value' => '消息内容'],
    'time2' => ['value' => '2023-01-01 10:00:00'],
]);

$response = $client->request($request);
```

## 高级用法

### 表达式语言函数

Bundle提供表达式语言函数用于动态消息发送：

```php
// 在您的表达式语言上下文中
sendWechatMiniProgramSubscribeMessage(user, 'template_id', ['param1' => 'value:context.data'])
```

### 使用模板

```php
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

// 获取可用模板
$templates = $subscribeTemplateRepository->findBy(['state' => 1]);

// 访问模板参数
foreach ($template->getSubscribeParams() as $param) {
    echo $param->getName() . ': ' . $param->getExample();
}
```

### 事件处理

Bundle在消息处理过程中分发事件：

```php
use WechatMiniProgramSubscribeMessageBundle\Event\SubscribeMsgPopupEvent;

// 监听订阅消息弹窗事件
class SubscribeMessageListener
{
    public function onSubscribeMsgPopup(SubscribeMsgPopupEvent $event): void
    {
        // 处理事件
    }
}
```

## 命令

### `wechat:mini-program:sync-subscribe-categories`

从微信API同步订阅消息分类。

- **定时执行**：每2小时的第11分钟执行
- **功能**：获取并更新所有有效微信账户的分类

### `wechat-mini-program:sync-subscribe-template`

从微信API同步订阅消息模板。

- **定时执行**：每4小时的第15分钟执行
- **功能**：获取模板及其参数

## 管理界面

通过 EasyAdmin 控制器访问管理界面：

- **订阅分类**：管理消息分类
- **订阅模板**：管理消息模板
- **订阅参数**：配置模板参数
- **消息日志**：查看已发送消息历史
- **发送日志**：监控消息投递状态

## 安全

此Bundle处理敏感的微信API凭证。请确保：

- 保护您的微信API凭证安全
- 使用环境变量存储敏感配置
- 定期轮换API密钥
- 监控管理界面的访问
- 在发送消息前验证所有用户输入

## 贡献

我们欢迎贡献！请：

1. Fork仓库
2. 创建功能分支
3. 为您的更改编写测试
4. 遵循PSR-12编码标准
5. 提交拉取请求

## 依赖项

此Bundle需要以下主要依赖项：

- **PHP**: ^8.1
- **Symfony**: ^6.4
- **Doctrine ORM**: ^3.0
- **EasyAdmin Bundle**: ^4
- **WeChat Mini Program Bundle**: tourze/wechat-mini-program-bundle

完整的依赖项列表请参见 `composer.json`。

## 许可证

此Bundle采用MIT许可证。有关详细信息，请参阅[LICENSE](LICENSE)文件。

