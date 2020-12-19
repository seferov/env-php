<?php

declare(strict_types=1);

namespace Seferov\DotEnv;

use function parse_ini_file;

final class DotEnv
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var array<string, string>|null
     */
    private $values;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return array<string, string>
     */
    public function asArray(): array
    {
        $array = parse_ini_file($this->path);
        if (!$array) {
            throw new \Exception();
        }

        return $array;
    }

    public function add(string $key, string $value): void
    {
        if (!$this->values) {
            $this->values = $this->asArray();
        }

        $this->values[$key] = $value;
    }

    public function write(): void
    {
        if (!$this->values) {
            return;
        }

        $content = '';
        foreach ($this->values as $key => $value) {
            $content .= $key.'='.$value.\PHP_EOL;
        }

        if (!$handle = fopen($this->path, 'w')) {
            throw new WriteException();
        }

        $success = fwrite($handle, $content);
        fclose($handle);

        if (!$success) {
            throw new WriteException();
        }
    }
}
