<?php

$finder = new PhpCsFixer\Finder();
$finder->in([__DIR__ . '/Bundle/*/src', __DIR__ . '/Skeleton/src']);

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony' => true,
        '@DoctrineAnnotation' => true,
        'phpdoc_summary' => false,
        'no_unneeded_final_method' => false,
        'no_superfluous_phpdoc_tags' => true,
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_to_comment' => false,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'fully_qualified_strict_types' => true,
        'trailing_comma_in_multiline' => false,
        'header_comment' => [
            'header' => ''
        ],
        'blank_line_before_statement' => []
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);