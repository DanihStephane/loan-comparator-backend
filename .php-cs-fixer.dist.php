<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src/')
    ->in(__DIR__ . '/config/');

// La documentation : https://mcampbell508.github.io/phpcsfixer-rules/
return (new Config())
    ->setRules([
        '@Symfony' => true,
        'no_break_comment' => false,
        'phpdoc_var_without_name' => false,
        'phpdoc_to_comment' => false,
        'phpdoc_summary' => false,
        'array_syntax' => ['syntax' => 'short'],
        'no_superfluous_phpdoc_tags' => false,
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align',
                '='  => 'align',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'single_quote' => false,
    ])
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setCacheFile(__DIR__ . '/php_cs.cache')
    ->setFinder($finder);
