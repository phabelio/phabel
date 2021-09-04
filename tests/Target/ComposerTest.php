<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class ComposerTest extends TestCase
{
    /**
     * Run command, exiting with error code if it fails.
     *
     * @param string $cmd
     * @return void
     */
    private static function r(string $cmd): void
    {
        fwrite(STDERR, "> $cmd".PHP_EOL.PHP_EOL);
        \passthru($cmd, $ret);
        if ($ret) {
            die($ret);
        }
    }

    private static function echo(string $msg): void
    {
        fwrite(STDERR, PHP_EOL.PHP_EOL."> =========== $msg ===========".PHP_EOL.PHP_EOL);
    }

    private static string $cwd;

    private static function backToCwd(): void {
        chdir(self::$cwd);
    }
    
    public static function setUpBeforeClass(): void
    {
        self::$cwd = getcwd();
        self::r('rm -rf ../phabelComposer');
        mkdir('../phabelComposer');
        mkdir('../phabelComposer/test1');
        mkdir('../phabelComposer/test2');
        $branch = \trim(\shell_exec("git rev-parse --abbrev-ref HEAD"));
        file_put_contents('../phabelComposer/test1/composer.json', json_encode([
            "name" => 'phabel/test1',
            "minimum-stability" => "dev",
            'require' => [
                "php" => ">=8.0"
            ],
            'require-dev' => [
                'phabel/phabel' => "dev-$branch"
            ],
            'autoload' => [
                'files' => ['./hello.php']
            ],
            'repositories' => [[
                'type' => 'path',
                'url' => '../../phabel',
                'symlink' => true
            ]]
        ]));
        file_put_contents('../phabelComposer/test2/composer.json', json_encode([
            "name" => 'phabel/test2',
            "minimum-stability" => "dev",
            'require' => [
                'php' => '>=5.6'
            ],
            'repositories' => [[
                'type' => 'path',
                'url' => '../../phabel',
                'symlink' => true
            ], [
                'type' => 'git',
                'url' => '../test1',
                'symlink' => true
            ]]
        ]));

        chdir('../phabelComposer/test1/');
        file_put_contents('hello.php', '<?php (fn (): null|int => print("Hello!"))();');
        file_put_contents('.gitignore', "vendor\ncomposer.lock");
        self::r('git init');
        self::r('git add -A');
        self::r('git commit -m "First commit"');
        self::r('git tag 1.0.0');

        self::r('composer update --ignore-platform-reqs');
        self::r('vendor/bin/phabel publish --dry');

        self::r("git checkout 1.0.0.9998");
        $json = json_decode(file_get_contents('composer.json'), true);
        $json['require']['phabel/phabel'] = "dev-$branch";
        file_put_contents('composer.json', json_encode($json));
        self::r('git commit -am Fix');
        self::r('git tag -d 1.0.0.9998');
        self::r('git tag 1.0.0.9998');
        self::r('git checkout 1.0.0');

        self::backToCwd();
    }

    protected function tearDown(): void
    {
        $this->backToCwd();
    }

    private function testRequire(string $branch, bool $cleanup = true): void
    {
        $this->backToCwd();
        chdir('../phabelComposer/test2/');
        $this->r("composer require phabel/test1:$branch");
        $this->assertEquals('Hello!', shell_exec('php vendor/autoload.php'));
        if ($cleanup) {
            $this->r('rm -rf vendor');
            $this->r("composer remove -n phabel/test1");
        }
    }
    private function testRequireFull(string $branch): void
    {
        if ($branch === '1.0.0.9999' && PHP_MAJOR_VERSION < 8) {
            $this->assertTrue(true);
            return;
        }
        $this->echo("Add phabel, remove phabel");
        $this->testRequire($branch);

        $this->echo("Add another package");
        $this->r('composer require danog/tg-file-decoder');

        $this->echo("Add phabel, keep phabel");
        $this->testRequire($branch, false);

        $this->echo("Remove another package, keep phabel");
        $this->r('composer remove danog/tg-file-decoder');

        $this->echo("Remove phabel");
        $this->r('rm -rf vendor/phabel/test1');
        $this->r("composer remove -n phabel/test1");

        $this->echo("Add phabel, keep phabel");
        $this->testRequire($branch, false);
        
        $this->echo("Add another package");
        $this->r('composer require danog/tg-file-decoder');
        $this->assertEquals('Hello!', shell_exec('php vendor/autoload.php'));

        $this->echo("Remove phabel, keep another package");
        $this->r('rm -rf vendor/phabel/test1');
        $this->r("composer remove -n phabel/test1");

        $this->echo("Remove another package");
        $this->r('composer remove danog/tg-file-decoder');
    }

    public function testRequireBasic(): void
    {
        $this->testRequireFull('1.0.0.9999');
    }

    
    public function testRequireTranspiled(): void
    {
        $this->testRequireFull('1.0.0.9998');
    }
    public function testRequireTranspiledAuto(): void
    {
        $this->testRequireFull('^1.0.0');
    }
}