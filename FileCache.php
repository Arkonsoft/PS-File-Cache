<?php

/**
 * NOTICE OF LICENSE
 *
 * This file is licensed under the Software License Agreement.
 *
 * With the purchase or the installation of the software in your application
 * you accept the license agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Arkonsoft
 * @copyright 2024 Arkonsoft
 */

namespace Arkonsoft\PsModule\FileCache;

if (!defined('_PS_VERSION_')) {
    exit;
}

class FileCache
{
    /**
     * @var string $cache_path Path to the cache directory
     */
    private $cache_path;

    /**
     * @param string $cache_path Path to the cache directory
     */
    public function __construct(string $cache_path)
    {
        $this->cache_path = $cache_path;
    }

    /**
     * Check whether the cache file exists and is not empty
     * @param string $key Cache key
     * @return bool True if the cache file exists and is not empty, false otherwise
     */
    public function isStored(string $key): bool
    {
        if(file_exists($this->cache_path . $key . '.txt') && filesize($this->cache_path . $key . '.txt') > 0){
            return true;
        }
        return false;
    }

    /**
     * Check whether the cache file is expired over the specified time difference
     * @param string $key Cache key
     * @param int $time_diff Time difference in seconds
     * @return bool True if the cache file is expired, file failed to open or cannot acquire file time, false otherwise
     */
    public function isExpired(string $key, int $time_diff = 3600): bool
    {
        if(!file_exists($this->cache_path . $key . '.txt')){
            return false;
        }

        $filetime = filemtime($this->cache_path . $key . '.txt');
        if(empty($filetime)){
            return false;
        }

        $now = time();
        $diff = $now - $filetime;

        if($diff > $time_diff){
            return true;
        }
        return false;
    }

    /**
     * Retrieve the cache value
     * In case of objects/arrays remember to json_decode it after retrieving
     * @param string $key Cache key
     * @return string|false Cache value if the cache file exists and is not empty, false otherwise
     */
    public function retrieve(string $key)
    {
        if (!empty($this->isStored($this->cache_path . $key . '.txt'))) {
            return false;
        }

        return file_get_contents($this->cache_path . $key . '.txt');
    }

    /**
     * Store the cache value
     * In case of objects/arrays remember to json_encode it before storing
     * @param string $key Cache key
     * @param string $value Cache value
     * @return false|int False if the cache file cannot be opened, int if the cache value is successfully stored
     */
    public function store(string $key, string $value): bool
    {
        $fp = fopen($this->cache_path . $key . '.txt', 'w');

        if (!$fp) {
            return false;
        }

        return fwrite($fp, $value, strlen($value)) && fclose($fp);
    }
}