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


## Examples

### Async/await syntax

Phabel also supports `async/await` syntax, powered by [Amp](https://amphp.org).  
Parallelize your code, using native `async/await` syntax and the [async Amp libraries](https://github.com/amphp) for fully concurrent networking, I/O, database access in pure, native PHP!  


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


## CLI API

When you run:  
```
composer require --dev phabel/phabel
```

Phabel automatically edits composer.json, adding some configuration parameters, and raising the minimum supported PHP version to `8.0`.  

Then, the following commands are available:

### `publish`

Publishes a transpiled version of your package+dependencies.  

💡 Your PHP 7 users can now install your PHP 8 library 💡  
💡 **All your dependencies will also be transpiled to the correct PHP version.** 💡

Internally, this command takes the newest (or provided) git tag, and then creates+pushes two subtags:  
* `tag.9999` - Points to exactly the same commit as `tag`.
* `tag.9998` - A new commit based on `tag`, with some changes to `composer.json`.

An example, say you have this `composer.json`:  
```json
{
    "name": "phabel/package",
    "description": "An example package.",
    "type": "project",
    "require": {
        "php": ">=8.0",
        "vendor/new-php8-package": "^1",
        "vendor/old-php7-package": "^1"
    },
    "require-dev": {
        "phabel/phabel": "^1"
    }
}
```

Note that `new-php8-package` only supports PHP 8.0+, and `old-php7-package` supports PHP 7.0+.  

Here's what happens when a user requires `phabel/package:^tag` on:
* PHP 8.0: the unprocessed commit @ `tag.9999` is installed.  
* PHP 7 (or lower versions): `tag.9998` is loaded, triggering Phabel's composer plugin, which:  
  * Transpiles `phabel/package` towards PHP 7.
  * Recursively transpiles `vendor/new-php8-package` and all its dependencies.
  * Composer still takes care of dependency and requirement resolution, so transpilation only occurs when there is no other choice.  
  * All dependencies in the `vendor` folder are checked for [covariance and contravariance](https://www.php.net/manual/en/language.oop5.variance.php) conflicts, which are immediately resolved.  


Note that as phabel adds support for lower and lower PHP versions, your package will automatically gain support for those versions, too, no need to republish it!  

#### Usage:
```bash
vendor/bin/phabel publish [options] [--] [<source>]
```

Arguments:
* `source` - *Optional* source tag name, defaults to the newest tag.

Options:
* `-r, --remote[=REMOTE]` - Remote where to push tags, defaults to the upstream of the current branch.
* `-d, --dry|--no-dry` - Whether to skip pushing tags to any remote.


### `run`

This commands simply transpiles the specified file or directory towards the specified PHP version.   
This command is useful for playing around with phabel, or creating a transpiled phar file.  
Always make sure to also transpile the `vendor` directory when creating a phar, to automatically resolve [covariance and contravariance](https://www.php.net/manual/en/language.oop5.variance.php) conflicts.  

[`publish`](#publish) is a much simpler version of this command, which automatically transpiles the package (and all dependencies!) upon Composer installation.  

#### Usage:

```bash
vendor/bin/phabel run [options] [--] <input> <output>
```

Arguments:
* `input` - Input path
* `output` - Output path

Options:
* `--target[=TARGET]` - Target PHP version, defaults to the lowest PHP version supported by Phabel
* `-i, --install|--no-install` - Whether to install required dependencies automatically
* `-j, --parallel[=PARALLEL]` - _Experimental:_ Number of threads to use for transpilation, 1 by default


## Projects using phabel

[Ping us](https://github.com/phabelio/phabel/issues/new) if you want your project to be added to this list!

* [MadelineProto](https://github.com/danog/MadelineProto)