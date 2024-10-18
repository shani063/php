<?php

/**
 * @author Khoa Bui (khoaofgod)  <khoaofgod@gmail.com> https://www.phpfastcache.com
 * @author Georges.L (Geolim4)  <contact@geolim4.com>
 */

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Exceptions\PhpfastcacheInvalidConfigurationException;
use Phpfastcache\Tests\Helper\TestHelper;

chdir(__DIR__);
require_once __DIR__ . '/../vendor/autoload.php';
$testHelper = new TestHelper('Custom key hash function');

function myAwesomeHashFunction($string){
    return 'customHash.' . sha1($string);
}

$cacheInstance = CacheManager::getInstance('Files', new ConfigurationOption(['defaultKeyHashFunction' => 'myAwesomeHashFunction']));

$item = $cacheInstance->getItem(str_shuffle(uniqid('pfc', true)));
$item->set(true)->expiresAfter(300);
$cacheInstance->save($item);

if($item->getEncodedKey() === 'customHash.' . sha1($item->getKey())){
    $testHelper->assertPass('The custom key hash function returned expected hash string: ' . $item->getEncodedKey());
}else{
    $testHelper->assertFail('The custom key hash function returned unexpected hash string: ' . $item->getEncodedKey());
}

$testHelper->terminateTest();
