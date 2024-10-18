<?php

/**
 * @author Khoa Bui (khoaofgod)  <khoaofgod@gmail.com> https://www.phpfastcache.com
 * @author Georges.L (Geolim4)  <contact@geolim4.com>
 */

use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Entities\ItemBatch;

chdir(__DIR__);
require_once __DIR__ . '/../../vendor/autoload.php';

$driverInstance = CacheManager::getInstance('Files', new ConfigurationOption([
  'preventCacheSlams' => true,
  'cacheSlamsTimeout' => 15
]));

/**
 * Emulate an active ItemBatch
 */
$batchItem = $driverInstance->getItem('TestCacheSlamsProtection');
$batchItem->set(new ItemBatch($batchItem->getKey(), new \DateTime()))->expiresAfter(3600);
$driverInstance->save($batchItem);

sleep(mt_rand(5, 15));

$batchItem->set(1337);
$driverInstance->save($batchItem);

exit(0);