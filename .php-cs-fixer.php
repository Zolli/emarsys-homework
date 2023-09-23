<?php

use PhpCsFixer\{
    Finder,
    Config,
};

$finder = Finder::create()
    ->in([
        __DIR__ . DIRECTORY_SEPARATOR . 'src',
        __DIR__ . DIRECTORY_SEPARATOR . 'tests'
    ]);

$config = new Config();
return $config->setRules([
    '@PER-CS2.0' => true,
    'strict_param' => true,
    'array_syntax' => ['syntax' => 'short'],
])->setFinder($finder);
