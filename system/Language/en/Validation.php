<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

// Validation language settings
return [
    // Core Messages
    'noRuleSets'      => 'No rulesets specified in Validation configuration.',
    'ruleNotFound'    => '{0} is not a valid rule.',
    'groupNotFound'   => '{0} is not a validation rules group.',
    'groupNotArray'   => '{0} rule group must be an array.',
    'invalidTemplate' => '{0} is not a valid Validation template.',

    // Rule Messages
    'bloquearReservado'                 => 'Você não pode usar palavras reservadas.',
    'alpha'                 => 'O {field} deve conter apenas letras.',
    'alpha_dash'            => 'O {field} deve conter apenas alfanuméricos, _, and #.',
    'alpha_numeric'         => 'O {field} deve conter apenas alfanuméricos',
    'alpha_numeric_punct'   => 'O {field} deve conter apenas alfanuméricos, espaço, e  ~ ! # $ % & * - _ + = | : . ',
    'alpha_numeric_space'   => 'O {field} deve conter apenas alfanuméricos e espaço.',
    'alpha_space'           => 'O {field} deve conter apenas letras and espaço.',
    'decimal'               => 'O {field} números decimais.',
    'differs'               => 'The {field} field must differ from the {param} field.',
    'equals'                => 'The {field} field must be exactly: {param}.',
    'exact_length'          => 'The {field} field must be exactly {param} characters in length.',
    'greater_than'          => 'The {field} field must contain a number greater than {param}.',
    'greater_than_equal_to' => 'The {field} field must contain a number greater than or equal to {param}.',
    'hex'                   => 'The {field} field may only contain hexidecimal characters.',
    'in_list'               => '{field} deve conter um dos caracteres: {param}.',
    'integer'               => 'O {field} deve conter apenas inteiros.',
    'is_natural'            => 'O {field} deve conter apenas dígitos.',
    'is_natural_no_zero'    => 'O {field} deve conter apenas digitos e deve ser maior que zero.',
    'is_not_unique'         => 'The {field} field must contain a previously existing value in the database.',
    'is_unique'             => '{field} deve possuir valur único.',
    'less_than'             => '{field} deve ser menor que {param}.',
    'less_than_equal_to'    => '{field} deve ser menor ou igual à {param}.',
    'matches'               => '{field} não coincide com {param} .',
    'max_length'            => '{field} não pode exceder {param} caracteres.',
    'min_length'            => '{field} deve ter no mínimo {param} caracteres.',
    'not_equals'            => ' {field} não pode ser igual à: {param}.',
    'not_in_list'           => ' {field} não deve conter os seguintes caracteres: {param}.',
    'numeric'               => ' {field} deve conter somente números válidos.',
    'regex_match'           => ' {field} field is not in the correct format.',
    'required'              => ' {field} deve ser informado',
    'required_with'         => ' {field} field is required when {param} is present.',
    'required_without'      => ' {field} field is required when {param} is not present.',
    'string'                => ' {field} field must be a valid string.',
    'timezone'              => ' {field} field must be a valid timezone.',
    'valid_base64'          => ' {field} field must be a valid base64 string.',
    'valid_email'           => ' {field} deve conter um email válido.',
    'valid_emails'          => ' {field} deve conter um emails válidos.',
    'valid_ip'              => '{field} deve conter um IP válido.',
    'valid_url'             => 'A {field} deve conter uma URL válidaL.',
    'valid_date'            => '{field} deve conter uma data válida.',

    // Credit Cards
    'valid_cc_num' => '{field} não parece ser um número de cartão de crédito válido.',

    // Files
    'uploaded' => '{field} Não é um arquivo válido.',
    'max_size' => '{field} é um arquivo muito grande.',
    'is_image' => '{field} não é válido, carregue uma imagem.',
    'mime_in'  => '{field} não tem um "mime type" válido.',
    'ext_in'   => '{field} não tem uma extenção válida.',
    'max_dims' => '{field} ou não é uma imagem, ou é muito pequena ou muito grande.',
];
