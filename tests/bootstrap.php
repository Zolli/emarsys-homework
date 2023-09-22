<?php

$autoloadFile = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

if (!file_exists($autoloadFile)) {
    echo 'Dependencies not installed!';

    exit(1);
}

include_once $autoloadFile;
