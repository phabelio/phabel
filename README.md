# Phabel

**Write and deploy modern PHP 8 code, today.**

This is a transpiler that allows native usage of PHP 8+ features and especially syntax in projects and libraries, while allowing maintainers to publish a version targeting lower versions of php.

The transpiler seamlessly hooks into composer to transpile the package (and all dependencies down the current branch of the dependency tree!) on installation, on the user's machine, targeting the user's specific PHP version.

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

## Supported PHP versions

Source:
* ✅ 8.0+  

Target:
* ✅ 7.1+  
* 🐘 5.6, 7.0 in final testing stage.  
* 💡 5.4, 5.5 support coming soon!  

**No additional commands are required to add support for older versions**: just `composer update` 😄



