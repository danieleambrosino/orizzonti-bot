<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        'declare_strict_types' => true,
        'php_unit_test_class_requires_covers' => false,
    ])
    ->setFinder(PhpCsFixer\Finder::create()->in(__DIR__))
    ->setIndent("\t")
;
