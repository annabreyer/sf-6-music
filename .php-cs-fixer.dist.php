<?php

$finder = PhpCsFixer\Finder::create()
                           ->in(__DIR__)
;

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12'                                           => true,
    '@DoctrineAnnotation'                              => true,
    '@Symfony'                                         => true,
    '@PhpCsFixer'                                      => true,
    '@PHP80Migration'                                  => true,
    '@PHP80Migration:risky'                            => true,
    'mb_str_functions'                                 => true,
    'array_syntax'                                     => ['syntax' => 'short'],
    'self_accessor'                                    => true,
    'date_time_immutable'                              => true,
    'native_constant_invocation'                       => true,
    'control_structure_continuation_position'          => ['position' => 'same_line'],
    'combine_nested_dirname'                           => true,
    'date_time_create_from_format_call'                => true,
    'implode_call'                                     => true,
    'native_function_invocation'                       => true,
    'no_useless_sprintf'                               => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'phpdoc_to_param_type'                             => true,
    'phpdoc_to_property_type'                          => true,
    'declare_equal_normalize'                          => ['space' => 'single'],
    'declare_parentheses'                              => true,
    'dir_constant'                                     => true,
    'error_suppression'                                => true,
    'function_to_constant'                             => true,
    'is_null'                                          => true,
    'no_unset_on_property'                             => true,
    'no_homoglyph_names'                               => true,
    'operator_linebreak'                               => ['only_booleans' => false, 'position' => 'beginning'],
    'ternary_to_elvis_operator'                        => true,
    'declare_strict_types'                             => true,
    'strict_comparison'                                => true,
    'strict_param'                                     => true,
    'no_trailing_whitespace_in_string'                 => true,
    'string_length_to_empty'                           => true,
    'binary_operator_spaces'                           => ['operators' => [
        '=>' => 'align',
        '+=' => 'align_single_space',
        '=' => 'align_single_space']],
])
              ->setFinder($finder)
;
