<?php

declare(strict_types=1);

namespace Seferov\DotEnv\Tests;

use PHPUnit\Framework\TestCase;
use Seferov\DotEnv\DotEnv;

class DotEnvTest extends TestCase
{
    private const FILE_PATH = __DIR__.'/.env';

    public function setUp(): void
    {
        file_put_contents(self::FILE_PATH, '');
    }

    /**
     * @covers \Seferov\DotEnv\DotEnv::asArray
     */
    public function testAsArray(): void
    {
        $content = <<<'EOD'
# some comment
# some comment with $sign
# some more comment
FOO=BAR
TOKEN=abc123abc

EOD;

        file_put_contents(self::FILE_PATH, $content);

        $dotEnv = new DotEnv(self::FILE_PATH);
        $this->assertSame([
            'FOO' => 'BAR',
            'TOKEN' => 'abc123abc',
        ], $dotEnv->asArray());
    }

    /**
     * @covers \Seferov\DotEnv\DotEnv::add
     * @covers \Seferov\DotEnv\DotEnv::write
     */
    public function testOverwrite(): void
    {
        $content = <<<'EOD'
# some comment
FOO=BAR

# after new line
TOKEN=abc123abc

EOD;

        file_put_contents(self::FILE_PATH, $content);

        $dotEnv = new DotEnv(self::FILE_PATH);
        $dotEnv->add('TOKEN', '123');
        $dotEnv->write();

        $expectedContent = <<<EOT
FOO=BAR
TOKEN=123

EOT;

        $this->assertSame($expectedContent, file_get_contents(self::FILE_PATH));
    }

    /**
     * @covers \Seferov\DotEnv\DotEnv::add
     * @covers \Seferov\DotEnv\DotEnv::write
     */
    public function testWrite(): void
    {
        $dotEnv = new DotEnv(self::FILE_PATH);
        $dotEnv->add('LOREM', 'IPSUM');
        $dotEnv->add('FOO', 'BAR');
        $dotEnv->write();

        $expectedContent = <<<EOT
LOREM=IPSUM
FOO=BAR

EOT;

        $this->assertSame($expectedContent, file_get_contents(self::FILE_PATH));
    }
}
