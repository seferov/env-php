<?php

declare(strict_types=1);

namespace Seferov\DotEnv;

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
        $file = fopen($this->path, 'r');
        while (!feof($file)) {
            $line = fgets($file);
            if (is_string($line) && 0 !== strpos($line, '#') && strpos($line, '=') > 0) {
                $a = explode('=', trim($line));
                $this->values[$a[0]] = $a[1];
            }
        }

        fclose($file);

        return $this->values ?? [];
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

    public function overwriteFromEnv(): void
    {
        if (!$this->values) {
            $this->values = $this->asArray();
        }

        foreach (($_ENV + $_SERVER) as $key => $value) {
            if (isset($this->values[$key])) {
                $this->values[$key] = $value;
            }
        }

        $this->write();
    }
}
