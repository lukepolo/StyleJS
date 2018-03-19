<?php

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        // addtional rules
        'array_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_before_semicolons' => true,
        'no_short_echo_tag' => true,
        'no_unused_imports' => true,
        'not_operator_with_successor_space' => true,
        'method_argument_space' => ['ensure_fully_multiline' => false]
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->notPath('bootstrap/cache')
            ->notPath('storage')
            ->notPath('vendor')
            ->in(__DIR__)
            ->name('*.php')
            ->notName('*.blade.php')
            ->notName('composer.lock')
            ->notName('_ide_helper.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    );