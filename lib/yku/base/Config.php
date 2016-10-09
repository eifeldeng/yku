<?php
class Config {
    private static $configCache;
    public static function loadConfig($configFile, $key = '') {
        $config_name = SERVER_NAME . $configFile;
        if (! isset ( self::$configCache [$config_name] )) {
            self::$configCache [$config_name] = include (SERVER_PATH ."/config/" . ENV . "/{$configFile}.php");
        }
        if ($key == '') {
            return self::$configCache [$config_name];
        }
        return self::$configCache [$config_name] [$key];
    }
}

?>