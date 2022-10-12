<?php

$header = <<<'EOF'
This file is part of the report-message-bot package.
EOF;

$config = new PhpCsFixer\Config();
return $config->setRiskyAllowed(true)
    ->setRules([
        '@PSR2'                                 => true,
        '@Symfony'                              => true,
        'array_syntax'                          => ['syntax' => 'short'],
        'combine_consecutive_unsets'            => true,
        // one should use PHPUnit methods to set up expected exception instead of annotations
        'header_comment'                        => ['header' => $header],
        'heredoc_to_nowdoc'                     => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else'                       => true,
        'no_useless_return'                     => true,
        'ordered_class_elements'                => true,
        'ordered_imports'                       => true,
        'php_unit_strict'                       => true,
        'phpdoc_add_missing_param_annotation'   => true,
        'no_trailing_comma_in_singleline_array' => true,
        'phpdoc_order'                          => true,
        'psr_autoloading'                       => true,
        'strict_comparison'                     => false,
        'strict_param'                          => false, //这里设置为true，发现in_array方法会默认加上第3个参数为true，这使得in_array会对前两个参数值的类型也会做严格的校验，建议设置为false
        'binary_operator_spaces'                => ['default' => 'align_single_space_minimal'],
        //'binary_operator_spaces' => true,
        'concat_space'                          => ['spacing' => 'one'],
        'no_empty_statement'                    => true,
        'simplified_null_return'                => true,
        'no_extra_blank_lines'                  => true,
        'increment_style'                       => false,
        'native_function_invocation'            => false,
        'phpdoc_separation'                     => false,
        'no_superfluous_phpdoc_tags'            => false,
        'phpdoc_indent'                         => true,
        'phpdoc_inline_tag_normalizer'          => true,
        'phpdoc_line_span'                      => true,
        'declare_strict_types'                  => true,
        'cast_spaces'                           => ['space' => 'single'],
        'phpdoc_align'                          => ['tags' => ['method', 'param', 'property', 'return', 'throws', 'type', 'var']]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->name('*.php')
            ->exclude('extend')
            ->exclude('vendor')
            ->exclude('FormBase')
            ->in(__DIR__)
    )
    ->setUsingCache(false);