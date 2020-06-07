<?php

namespace plugins\yzdlm_compress_image\lib;

use plugins\yzdlm_compress_image\lib\Tinify\Source;
use plugins\yzdlm_compress_image\lib\Tinify\AccountException;
use plugins\yzdlm_compress_image\lib\Tinify\Client;


class Tinify {

    const VERSION = "1.5.2";
    private static $key = NULL;
    private static $appIdentifier = NULL;
    private static $proxy = NULL;

    private static $compressionCount = NULL;
    private static $client = NULL;

    public static function setKey($key) {
        self::$key = $key;
        self::$client = NULL;
    }

    public static function setAppIdentifier($appIdentifier) {
        self::$appIdentifier = $appIdentifier;
        self::$client = NULL;
    }

    public static function setProxy($proxy) {
        self::$proxy = $proxy;
        self::$client = NULL;
    }

    public static function getCompressionCount() {
        return self::$compressionCount;
    }

    public static function setCompressionCount($compressionCount) {
        self::$compressionCount = $compressionCount;
    }

    public static function getClient() {
        if (!self::$key) {
            throw new AccountException("Provide an API key with Tinify\setKey(...)");
        }

        if (!self::$client) {
            self::$client = new Client(self::$key, self::$appIdentifier, self::$proxy);
        }

        return self::$client;
    }

    public static function setClient($client) {
        self::$client = $client;
    }

    public static function fromFile($path) {
        return Source::fromFile($path);
    }

    public static function fromBuffer($string) {
        return Source::fromBuffer($string);
    }

    public static function fromUrl($string) {
        return Source::fromUrl($string);
    }
}
