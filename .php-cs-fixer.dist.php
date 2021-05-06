<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PHP71Migration:risky' => true,
        '@PSR12' => true,
        'concat_space' => ['spacing' => 'one'],
        'psr_autoloading' => false,
        'no_useless_else' => true,
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => true],
        'list_syntax' => ['syntax' => 'short'],
        'linebreak_after_opening_tag' => true,
        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
    ])
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
    ->setFinder($finder);
