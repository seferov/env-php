<?php

declare(strict_types=1);

namespace Seferov\DotEnv\Tests;

use PHPUnit\Framework\TestCase;
use Seferov\DotEnv\DotEnv;

class DotEnvTest extends TestCase
{
    /**
     * @covers \Seferov\DotEnv\DotEnv::asArray
     */
    public function testAsArray(): void
    {
        $dotEnv = new DotEnv(__DIR__.'/fixtures/.env');
        $this->assertSame([
            'FOO' => 'BAR',
            'TOKEN' => 'abc123abc',
        ], $dotEnv->asArray());
    }

    /**
     * @covers \Seferov\DotEnv\DotEnv::add
     * @covers \Seferov\DotEnv\DotEnv::write
     */
    public function testWrite(): void
    {
        $path = __DIR__.'/fixtures/.env.empty';
        $dotEnv = new DotEnv($path);
        $dotEnv->add('LOREM', 'IPSUM');
        $dotEnv->add('FOO', 'BAR');
        $dotEnv->write();

        $expectedContent = <<<EOT
LOREM=IPSUM
FOO=BAR

EOT;

        $this->assertSame($expectedContent, file_get_contents($path));
    }
}
