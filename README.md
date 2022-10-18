<div align="center">
    <h1>PHP Frontend Debugger & Exception Handler</h1>
    <p>Lightweight frontend debugger and exception handler for PHP.</p>
    <hr>
</div>

## Features
* Debug/Print any variable to the frontend debugger box.
* Show formatted stacktrace of exceptions.

## Installation & Usage
### Composer installation: 
`composer req "noelclick/php-frontend-debugger"`
### Set exception handler 
```php
// Composer autoloader
require "./vendor/autoload.php"; 

\NoelClick\PhpFrontendDebugger\ExceptionHandler::getInstance()
    ->setCondition(true) // Optionally, you can set conditions with the `setCondition()` method.
    ->handle();
```
### Print variable (Will be printed if an error occurs)
```php
// Composer autoloader
require "./vendor/autoload.php"; 

$fooBar = ["foo", "bar"];

\NoelClick\PhpFrontendDebugger\FrontendDebugger::getInstance()
        ->insert($fooBar, "FooBar"); // Optionally, you can also specify a title.
```

## Changelog
All notable changes to this project will be documented in the [CHANGELOG.md](CHANGELOG.md) file.

## Copyright
&copy; Copyright 2022 by Noel Kayabasli