# ps-file-cache

A simple file cache for PHP

## Installation

```composer install arkonsoft/ps-file-cache```

## Usage

Create a new instance of the cache:
```
use Arkonsoft\PsModule\FileCache;
...
$cache = new FileCache(__DIR__ . '/cache/');
```

Store a value in the cache:
```
$cache->store('key', 'value');
```

Get a value from the cache:
```
$value = $cache->retrieve('key');
```

Retrieve method will also check whether the key is expired or not. You can turn off this feature by passing false as the second argument:
```
$value = $cache->retrieve('key', false);
```

### Important

store() method uses json_encode() to serialize data. You can store any data beside "resource" type. If you'll try to do it exception will be thrown.

## Additional methods

### Lifetime
Base lifetime is set to 3600 seconds (1h).

You can change it in the constructor:
```
$cache = new FileCache('path', 6000);
```
Or later with:
```
$cache->setLifetime(6000);
```

### IsStored

Check if a key is stored in the cache and is not empty:
```
$cache->isStored('key');
```

### IsExpired

Check if a key is stored in the cache and is expired:
```
$cache->isExpired('key');
```
