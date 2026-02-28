<?php

/**
 * Shared PHP CS Fixer rules.
 *
 * Required by both .php-cs-fixer.dist.php (Pro root) and
 * admin-columns/.php-cs-fixer.dist.php (free core).
 * Edit only this file to update the ruleset for both.
 */
return [
    '@PSR12'                       => true,

    // 'declare_strict_types'         => true,

    // Brace style (match project style)
    'curly_braces_position'        => [
        'classes_opening_brace'             => 'next_line_unless_newline_at_signature_end',
        'functions_opening_brace'           => 'next_line_unless_newline_at_signature_end',
        'control_structures_opening_brace'  => 'same_line',
        'anonymous_functions_opening_brace' => 'same_line',
        'anonymous_classes_opening_brace'   => 'next_line_unless_newline_at_signature_end',
    ],

    // Imports
    'ordered_imports'              => [
        'sort_algorithm' => 'alpha',
        'case_sensitive' => false,
    ],
    'no_unused_imports'            => true,

    // Arrays
    'array_syntax'                 => ['syntax' => 'short'],
    'trailing_comma_in_multiline'  => ['elements' => ['arrays']],
    'binary_operator_spaces'       => [
        'operators' => [
            '=>' => 'align_single_space_minimal',
        ],
    ],

    // General cleanup
    'constant_case'                => ['case' => 'lower'],
    'elseif'                       => true,
    'blank_line_after_opening_tag' => true,
    'no_extra_blank_lines'         => true,
    'switch_case_space'            => false,
    'statement_indentation'        => false,
];