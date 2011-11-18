#!/usr/bin/env php
<?php
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;

require 'bootstrap.php';

$loader = new Loader();
$loader->loadFromDirectory('Fixtures');

$executor = new ORMExecutor($em);
$executor->execute($loader->getFixtures(), true); // Append. The purger is not used.