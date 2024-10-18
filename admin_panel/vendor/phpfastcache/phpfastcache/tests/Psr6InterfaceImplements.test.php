<?php

/**
 * @author Khoa Bui (khoaofgod)  <khoaofgod@gmail.com> https://www.phpfastcache.com
 * @author Georges.L (Geolim4)  <contact@geolim4.com>
 */

use Phpfastcache\CacheManager;
use Phpfastcache\Tests\Helper\TestHelper;
use Psr\Cache\CacheItemPoolInterface;

chdir(__DIR__);
require_once __DIR__ . '/../vendor/autoload.php';
$testHelper = new TestHelper('PSR6 Interface Implements');
$defaultDriver = (!empty($argv[1]) ? ucfirst($argv[1]) : 'Files');

/**
 * Testing memcached as it is declared in .travis.yml
 */
$driverInstance = CacheManager::getInstance($defaultDriver);

if (!is_object($driverInstance)) {
    $testHelper->assertFail('CacheManager::getInstance() returned an invalid variable type:' . gettype($driverInstance));
}else if(!($driverInstance instanceof CacheItemPoolInterface)){
    $testHelper->assertFail('CacheManager::getInstance() returned an invalid class:' . get_class($driverInstance));
}else{
    $testHelper->assertPass('CacheManager::getInstance() returned a valid CacheItemPoolInterface object: ' . get_class($driverInstance));
}

$testHelper->terminateTest();
