# Better Laravel

![test](https://github.com/kafkiansky/better-laravel/workflows/test/badge.svg?event=push)
[![static analysis](https://github.com/kafkiansky/better-laravel/workflows/static%20analysis/badge.svg)](https://github.com/kafkiansky/better-laravel/actions?query=workflow%3A%22static+analysis%22)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/kafkiansky/better-laravel.svg?style=flat-square)](https://packagist.org/packages/kafkiansky/better-laravel)

### Contents

- [**Installation**](#installation)
- [**Motivation**](#motivation)
- [**Hooks**](#hooks)
  - [**DontExtendApplicationEventServiceProvider**](#DontExtendApplicationEventServiceProvider)
  - [**DontUseEnvOutsideConfiguration**](#DontUseEnvOutsideConfiguration)
  - [**ConfigurationOptionMustExists**](#ConfigurationOptionMustExists)
- [**Testing**](#testing)
- [**License**](#license)

## Installation

```bash
composer require kafkiansky/better-laravel --dev
```

## Motivation

Laravel is a framework focused on ease of development. However, while providing many simple tools to quickly implement production ready solutions, it also adds more options to allow bugs. This package provides a set of plugins for the `Psalm` static analyzer, which covers many known issues.

## Hooks

#### DontExtendApplicationEventServiceProvider

How often have you made the mistake of inheriting the right `EventServiceProvider`?
For example, you have a service provider `A` in which you register subscribers or listeners, and then inherit it in service provider `B` in which you register only listeners or only subscribers.
So you risk duplicating subscribers or listeners because `Laravel` will register them twice in the dispatcher.

The `DontExtendApplicationEventServiceProvider` hook will detect such service providers and prevent them from using inheritance from project event service providers.

#### DontUseEnvOutsideConfiguration

You have probably heard that the `env` function will always return null when caching a configuration. This is because Laravel does not load environment variables if it sees a cached configuration file in `bootstrap/cache/config.php`.
This hook works quite simply: if it sees calls to the `env` function outside the configuration files, it shows an error.

⚠️ **Important restriction: all configuration files must be stored in the `config` folder.**

#### ConfigurationOptionMustExists

Laravel allows you to work conveniently with configuration through dot notation. This is both convenient because it allows you to have unlimited nesting of the configuration, and inconvenient because no one validates such a configuration, and you can get the name wrong.
This hook parses all the configuration files of your project and checks that the options you are accessing really exist.

⚠️ **Important restriction: all configurations must be stored in the config folder and the file names must match the namespace (root) of the configuration.**

For example, you have the following config in a file called `api.php`:

```php
return [
    'http' => [
        'uri' => 'https://httpbin.org',
    ],
];
```

When accessing the `api.http.ur` configuration, you will see an error from `Psalm` that such configuration option is missing.

## Testing

``` bash
$ composer test
```  

## License

The MIT License (MIT). See [License File](LICENSE) for more information.