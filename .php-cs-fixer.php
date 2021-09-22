<?php declare(strict_types=1);

use PhpCsFixer\Fixer\FunctionNotation\NativeFunctionInvocationFixer;

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__])
    ->exclude('bundle/DoctrineMigrations')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'simplified_null_return' => false,
        'protected_to_private' => true,
        'phpdoc_align' => false,
        'phpdoc_separation' => false,
        'phpdoc_to_comment' => false,
        'mb_str_functions' => true,
        'linebreak_after_opening_tag' => false,
        'cast_spaces' => false,
        'blank_line_after_opening_tag' => false,
        'single_blank_line_before_namespace' => false,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_no_alias_tag' => false,
        'space_after_semicolon' => false,
        'no_break_comment' => false,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'php_unit_strict' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'align_multiline_comment' => true,
        'array_indentation' => true,
        'class_definition' => ['single_line' => false],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'declare_strict_types' => true,
        'no_alternative_syntax' => true,
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        'phpdoc_order' => true,
        'semicolon_after_instruction' => true,
        'native_function_invocation' => [
            'include' => [NativeFunctionInvocationFixer::SET_COMPILER_OPTIMIZED ],
            'scope' => 'namespaced',
            'strict' => false, // or remove this line, as false is default value
        ],
        'yoda_style' => true,
    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php_cs.cache')
;
