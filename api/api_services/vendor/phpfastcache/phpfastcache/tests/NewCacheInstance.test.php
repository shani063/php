<?php

/**
 * @author Khoa Bui (khoaofgod)  <khoaofgod@gmail.com> https://www.phpfastcache.com
 * @author Georges.L (Geolim4)  <contact@geolim4.com>
 */

use Phpfastcache\Core\Pool\ExtendedCacheItemPoolInterface;
use Phpfastcache\CacheManager;
use Phpfastcache\Tests\Helper\TestHelper;

chdir(__DIR__);
require_once __DIR__ . '/../vendor/autoload.php';
$testHelper = new TestHelper('New cache instance');
$defaultDriver = (!empty($argv[1]) ? ucfirst($argv[1]) : 'Files');

/**
 * Testing memcached as it is declared in .travis.yml
 */
$driverInstance = CacheManager::getInstance($defaultDriver);

if (!is_object($driverInstance) || !($driverInstance instanceof ExtendedCacheItemPoolInterface)) {
    $testHelper->assertFail('CacheManager::getInstance() returned wrong data hint:' . gettype($driverInstance));
}else{
    $testHelper->assertPass('CacheManager::getInstance() returned an expected object that implements ExtendedCacheItemPoolInterface');
}

$testHelper->terminateTest();
