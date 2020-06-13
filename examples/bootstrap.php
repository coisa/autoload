<?php

/** @var Composer\Autoload\ClassLoader $classLoader */
$classLoader = require \dirname(__DIR__) . '/src/bootstrap.php';

/** @var Psr\Log\LoggerInterface $logger */
$logger = require __DIR__ . '/logger.php';

/** @var mixed[] $config */
$config = require __DIR__ . '/config.php';
