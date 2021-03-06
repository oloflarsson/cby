#!/usr/bin/env php
<?php

/*
Inspiration was taken from: https://raw.github.com/symfony/symfony/master/vendors.php
*/

set_time_limit(0);

// These are the libs to download / update to.
// NOTE 1: Make sure the revisions are compatible with eachother. You can usualy find that information here: http://www.doctrine-project.org/blog/
// NOTE 2: Make sure there is a corresponding entry in .gitignore for each of the dependencies.
// NOTE 3: Make sure there is a class-loader entry in bootstrap.php
$libs = array(
	array('doctrine-common', 'git://github.com/doctrine/common.git', '2.1.2'),
	array('doctrine-dbal', 'git://github.com/doctrine/dbal.git', '2.1.3'),
	array('doctrine-orm', 'git://github.com/doctrine/doctrine2.git', '2.1.2'),
	array('doctrine-migrations', 'git://github.com/doctrine/migrations.git', '73c7570042fb8d0bb74281164c55ae5740562d4b'), // The one used 2012
	array('doctrine-data-fixtures', 'git://github.com/doctrine/data-fixtures.git', '3ef3f667cc7a4552beee2fc4abefd4da1a43557c'), // The one used 2012
	array('Symfony/Component/Console', 'git://github.com/symfony/Console.git', 'd7b1718424'), // 2.0.5
	array('Symfony/Component/Yaml', 'git://github.com/symfony/Yaml.git', '6d7a0b450f'), // 2.0.5
);

$libdir = dirname(__FILE__).'/lib';
if ( ! is_dir($libdir))
{
	mkdir($libdir, 0777, true);
}

foreach ($libs as $lib)
{
	list($name, $url, $rev) = $lib;

	$installDir = $libdir.'/'.$name;
	$install = false;
	if ( ! is_dir($installDir))
	{
		$install = true;
		echo "===== Installing $name =====\n";
		system(sprintf('git clone %s %s', escapeshellarg($url), escapeshellarg($installDir)));
	}

	if ( ! $install)
	{
        echo "===== Updating $name =====\n";
    }

    system(sprintf('cd %s && git fetch origin && git reset --hard %s', escapeshellarg($installDir), escapeshellarg($rev)));
}
echo "===== DONE =====\n";