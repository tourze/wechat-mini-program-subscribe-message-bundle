# WeChat Mini Program Subscribe Message Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![PHP Version Require](https://img.shields.io/packagist/php-v/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![License](https://img.shields.io/packagist/l/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-mini-program-subscribe-message-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-mini-program-subscribe-message-bundle)
[![Build Status](https://img.shields.io/github/actions/workflow/status/tourze/php-monorepo/ci.yml?branch=master&style=flat-square)](
https://github.com/tourze/php-monorepo/actions)
[![Coverage Status](https://img.shields.io/codecov/c/github/tourze/php-monorepo.svg?style=flat-square)](
https://codecov.io/gh/tourze/php-monorepo)

A Symfony bundle for managing WeChat Mini Program subscribe messages. This bundle provides 
comprehensive functionality for handling subscribe message categories, templates, and message 
sending with an intuitive admin interface.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Quick Start](#quick-start)
- [Advanced Usage](#advanced-usage)
  - [Expression Language Functions](#expression-language-functions)
  - [Working with Templates](#working-with-templates)
  - [Event Handling](#event-handling)
- [Commands](#commands)
  - [wechat:mini-program:sync-subscribe-categories](#wechatmini-programsync-subscribe-categories)
  - [wechat-mini-program:sync-subscribe-template](#wechat-mini-programsync-subscribe-template)
- [Admin Interface](#admin-interface)
- [Security](#security)
- [Contributing](#contributing)
- [Dependencies](#dependencies)
- [License](#license)

## Features

- **Subscribe Categories Management**: Automatically sync and manage subscribe message categories from WeChat API
- **Template Management**: Sync and manage subscribe message templates with parameters
- **Message Sending**: Send subscribe messages to users with expression language support
- **Admin Interface**: Complete EasyAdmin CRUD controllers for data management
- **Cron Job Integration**: Automatic synchronization with configurable schedules
- **Event System**: Event-driven architecture for message handling and logging
- **Expression Language**: Dynamic message content with expression language support

## Installation

Install the bundle via Composer:

```bash
composer require tourze/wechat-mini-program-subscribe-message-bundle
```

Add the bundle to your `config/bundles.php`:

```php
<?php
return [
    // ... other bundles
    WechatMiniProgramSubscribeMessageBundle\WechatMiniProgramSubscribeMessageBundle::class => ['all' => true],
];
```

## Configuration

The bundle integrates with the WeChat Mini Program Bundle configuration. Make sure you have configured 
your WeChat Mini Program account credentials:

```yaml
# config/packages/wechat_mini_program.yaml
wechat_mini_program:
    accounts:
        default:
            app_id: '%env(WECHAT_MINI_PROGRAM_APP_ID)%'
            secret: '%env(WECHAT_MINI_PROGRAM_SECRET)%'
```

Optionally, you can configure the bundle's specific settings:

```yaml
# config/packages/wechat_mini_program_subscribe_message.yaml
wechat_mini_program_subscribe_message:
    # Enable/disable automatic template synchronization
    auto_sync: true
    
    # Default settings for message sending
    default_settings:
        page: 'pages/index'
        miniprogram_state: 'formal'  # formal, trial, developer
```

## Quick Start

1. Configure your WeChat Mini Program account credentials in your WeChat Mini Program Bundle configuration.

2. Run the database migration to create required tables:

```bash
php bin/console doctrine:migrations:migrate
```

3. Sync subscribe categories and templates from WeChat API:

```bash
# Sync subscribe message categories
php bin/console wechat:mini-program:sync-subscribe-categories

# Sync subscribe message templates
php bin/console wechat-mini-program:sync-subscribe-template
```

4. Send a subscribe message:

```php
use WechatMiniProgramSubscribeMessageBundle\Request\SendSubscribeMessageRequest;
use WechatMiniProgramBundle\Service\Client;

$request = new SendSubscribeMessageRequest();
$request->setToUser('user_openid');
$request->setTemplateId('your_template_id');
$request->setPage('pages/index');
$request->setData([
    'thing1' => ['value' => 'Message content'],
    'time2' => ['value' => '2023-01-01 10:00:00'],
]);

$response = $client->request($request);
```

## Advanced Usage

### Expression Language Functions

The bundle provides expression language functions for dynamic message sending:

```php
// In your expression language context
sendWechatMiniProgramSubscribeMessage(user, 'template_id', ['param1' => 'value:context.data'])
```

### Working with Templates

```php
use WechatMiniProgramSubscribeMessageBundle\Repository\SubscribeTemplateRepository;

// Get available templates
$templates = $subscribeTemplateRepository->findBy(['state' => 1]);

// Access template parameters
foreach ($template->getSubscribeParams() as $param) {
    echo $param->getName() . ': ' . $param->getExample();
}
```

### Event Handling

The bundle dispatches events during message processing:

```php
use WechatMiniProgramSubscribeMessageBundle\Event\SubscribeMsgPopupEvent;

// Listen to subscribe message popup events
class SubscribeMessageListener
{
    public function onSubscribeMsgPopup(SubscribeMsgPopupEvent $event): void
    {
        // Handle the event
    }
}
```

## Commands

### `wechat:mini-program:sync-subscribe-categories`

Synchronizes subscribe message categories from WeChat API.

- **Schedule**: Every 2 hours at 11 minutes past the hour
- **Function**: Fetches and updates categories for all valid WeChat accounts

### `wechat-mini-program:sync-subscribe-template`

Synchronizes subscribe message templates from WeChat API.

- **Schedule**: Every 4 hours at 15 minutes past the hour  
- **Function**: Fetches templates and their parameters

## Admin Interface

Access the admin interface through EasyAdmin controllers:

- **Subscribe Categories**: Manage message categories
- **Subscribe Templates**: Manage message templates
- **Subscribe Parameters**: Configure template parameters
- **Message Logs**: View sent message history
- **Send Logs**: Monitor message delivery status

## Security

This bundle handles sensitive WeChat API credentials. Ensure:

- Keep your WeChat API credentials secure
- Use environment variables for sensitive configuration
- Regularly rotate API keys
- Monitor access to admin interfaces
- Validate all user inputs before sending messages

## Contributing

We welcome contributions! Please:

1. Fork the repository
2. Create a feature branch
3. Write tests for your changes
4. Follow PSR-12 coding standards
5. Submit a pull request

## Dependencies

This bundle requires the following main dependencies:

- **PHP**: ^8.1
- **Symfony**: ^6.4
- **Doctrine ORM**: ^3.0
- **EasyAdmin Bundle**: ^4
- **WeChat Mini Program Bundle**: tourze/wechat-mini-program-bundle

See `composer.json` for the complete list of dependencies.

## License

This bundle is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
