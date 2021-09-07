# Phabel [![phabel.io - PHP transpiler](https://phabel.io/badge)](https://phabel.io)

**Write and deploy modern PHP 8 code, today.**

This is a transpiler that allows native usage of PHP 8+ features and especially syntax in projects and libraries, while allowing maintainers to publish a version targeting lower versions of php.

The transpiler seamlessly hooks into composer to transpile the package (and all dependencies down the current branch of the dependency tree!) on installation, on the user's machine, targeting the user's specific PHP version.

Syntax/feature support:
* ✅ 8.0+  
* [async/await syntax](#asyncawait-syntax)
* Psalm-compatible generics coming soon :D

Created by [Daniil Gentili](https://daniil.it)

## Usage

```
composer require --dev phabel/phabel
```

You can now publish your packagist package, and it will be automatically transpiled to any PHP version supported by phabel.  

**After git tagging a new release, just run**:

```
vendor/bin/phabel publish
```

💡 Your PHP 7 users can now install your PHP 8 library 💡  
💡 **All your dependencies will also be transpiled to the correct PHP version.** 💡

## Supported PHP versions

Syntax/feature support:
* ✅ 8.0+  
* [async/await syntax](#asyncawait-syntax)

Target:
* ✅ 7.1+  
* 🐘 5.6, 7.0 in final testing stage.  
* 💡 5.4, 5.5 support coming soon!  

**No additional commands are required to add support for older versions**: just `composer update` 😄


## Async/await syntax

Phabel also supports `async/await` syntax, powered by [Amp](https://amphp.org).  
Parallelize your code, using native `async/await` syntax and the [async Amp libraries](https://github.com/amphp) for fully concurrent networking, I/O, database access in pure, native PHP!  

### Examples

#### File I/O

This example uses the [amphp/file](https://github.com/amphp/file) library:  

```php
<?php

// Write and read three files on your filesystem, in parallel
// Async/await syntax powered by phabel.io
// Concurrency powered by amphp.org

require 'vendor/autoload.php';

use Amp\Loop;
use function Amp\File\read;
use function Amp\File\write;

Loop::run(function () {
    // This is done in parallel!
    await [
        write('file1', 'contents1'),
        write('file2', 'contents2'),
        write('file3', 'contents3'),
    ];

    // This is also done in parallel!
    var_dump(await [
        read('file1'),
        read('file2'),
        read('file3'),
    ]);
});
```

You can publish this code as a Composer package and [have it automatically transpile on installation](#usage), or even transpile it manually:  
```bash
composer require amphp/file phabel/phabel
vendor/bin/phabel run input.php output.php
php output.php
```

