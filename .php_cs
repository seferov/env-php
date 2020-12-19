<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

return (new PhpCsFixer\Config())->setRules([
    '@Symfony' => true,
    'strict_param' => true,
    'declare_strict_types' => true,
    'array_syntax' => ['syntax' => 'short'],
])->setFinder($finder);
