<?php declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new Config())
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRules([
        '@DoctrineAnnotation' => true,
        '@PHP54Migration' => true,
        '@PHP56Migration:risky' => true,
        '@PHP70Migration' => true,
        '@PHP70Migration:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHP73Migration' => true,
        '@PHP74Migration' => true,
        '@PHP74Migration:risky' => true,
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PHP81Migration' => true,
        '@PHP82Migration' => true,
        '@PHP83Migration' => true,
        '@PHP84Migration' => true,
        '@PSR1' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'declare_parentheses' => true,
        'blank_line_after_opening_tag' => false,
        'linebreak_after_opening_tag' => false,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'list_syntax' => ['syntax' => 'short'],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => [
            'order' => [
                'constant_public',
                'constant_protected',
                'constant_private',
                'use_trait',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'ordered_imports' => [
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
        ],
        'strict_comparison' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'cast_spaces' => [
            'space' => 'none',
        ],
        'heredoc_indentation' => [
            'indentation' => 'same_as_start',
        ],
        'blank_line_between_import_groups' => false,
        'function_declaration' => false,
        'increment_style' => false,
        'comment_to_phpdoc' => false,
        'single_class_element_per_statement' => ['elements' => ['const']],
        'phpdoc_separation' => false,
        'ordered_traits' => false,
        'phpdoc_align' => false,
        'nullable_type_declaration_for_default_null_value' => false,
        'strict_param' => false,
    ]);

