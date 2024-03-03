<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('docker')
    ->exclude('tests/_support/_generated')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true,
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'cast_spaces' => ['space' => 'single'],
        'yoda_style' => false,
        'no_superfluous_phpdoc_tags' => false,
        'ordered_imports' => false,
        'phpdoc_align' => ['align' => 'left'],
        'single_line_throw' => false,
        'class_attributes_separation' => [
            'elements' => ['trait_import' => 'none'],
        ],
        'types_spaces' => ['space' => 'single'],
        //        'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
        'multiline_whitespace_before_semicolons' => false,
        'no_extra_blank_lines' => [
            'tokens' => [
                'break', 'case', 'continue', 'curly_brace_block',
                'default', 'extra', 'parenthesis_brace_block', 'return',
                'square_brace_block', 'switch', 'throw',
            ],
        ],
        'class_definition' => false,
        'blank_line_before_statement' => [
            'statements' => [
                'for',
                'foreach',
                'if',
                'switch',
                'while',
                'try',
                'declare',
                'return',
            ],
        ],
        'global_namespace_import' => [
            'import_classes' => true,
            'import_functions' => true,
            'import_constants' => true,
        ],
        'native_function_invocation' => [
            'scope' => 'all',
            'strict' => true,
            'include' => ['@all'],
            'exclude' => ['date', 'time', 'sleep'],
        ],
        'phpdoc_separation' => false,

        'nullable_type_declaration_for_default_null_value' => false,
        'operator_linebreak' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ;
