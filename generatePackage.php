<?php

error_reporting(E_ALL & ~E_DEPRECATED);

require_once('PEAR/PackageFileManager2.php');

PEAR::setErrorHandling(PEAR_ERROR_DIE);

$packagexml = new PEAR_PackageFileManager2;

$packagexml->setOptions(array(
    'baseinstalldir'    => '/',
    'simpleoutput'      => true,
    'packagedirectory'  => './',
    'filelistgenerator' => 'file',
    'ignore'            => array(
        'generatePackage.php',
        'phpunit.xml',
        'phpunit-bootstrap.php',
        'coverage/'
    ),
    'dir_roles' => array(
        'tests'     => 'test',
        'examples'  => 'doc'
    ),
    'exceptions' => array(
        'README' => 'doc',
    ),
));

$packagexml->setPackage('EC_XHProf');
$packagexml->setSummary('EC_XHProf is a simple class for managing XHProf runs.');
$packagexml->setDescription(
    'EC_XHProf is a simple class for managing XHProf runs and storing them according
to virtual host ServerNames.  You can also clear out runs.  See the examples
directory for examples.'
);

$packagexml->setChannel('empower.github.com/pirum');
$packagexml->setAPIVersion('0.2.0');
$packagexml->setReleaseVersion('0.2.0');

$packagexml->setReleaseStability('alpha');

$packagexml->setAPIStability('alpha');

$packagexml->setNotes('
* Added support for multiple vhost prefixes
');
$packagexml->setPackageType('php');
$packagexml->addRelease();

$packagexml->detectDependencies();

$packagexml->addMaintainer('lead',
                           'shupp',
                           'Bill Shupp',
                           'hostmaster@shupp.org');

$packagexml->setLicense('New BSD License',
                        'http://www.opensource.org/licenses/bsd-license.php');

$packagexml->setPhpDep('5.0.0');
$packagexml->setPearinstallerDep('1.4.0b1');
$packagexml->addExtensionDep('optional', 'xhprof');

$packagexml->generateContents();
$packagexml->writePackageFile();

?>
