<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__."/src",
        __DIR__."/tests"
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'yoda_style' => true,
    ])
    ->setFinder($finder)
    ;