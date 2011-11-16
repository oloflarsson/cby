#!/usr/bin/env php
<?php

/*
Inspiration was taken from: https://raw.github.com/symfony/symfony/master/vendors.php
*/

set_time_limit(0);

// These are the libs to download / update to.
// NOTE 1: Make sure the revisions are compatible with eachother. You can usualy find that information here: http://www.doctrine-project.org/blog/
// NOTE 2: Make sure there is a corresponding entry in .gitignore for each of the dependencies.
$libs = array(
	array('doctrine-common', 'git://github.com/doctrine/common.git', '2.1.2'),
	array('doctrine-dbal', 'git://github.com/doctrine/dbal.git', '2.1.3'),
	array('doctrine-orm', 'git://github.com/doctrine/doctrine2.git', '2.1.2'),
	array('doctrine-migrations', 'git://github.com/doctrine/migrations.git', 'origin/master'), // does not use tags.
	array('symfony-console', 'git://github.com/symfony/Console.git', 'd7b1718424'),
	array('symfony-yaml', 'git://github.com/symfony/Yaml.git', '6d7a0b450f'),
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