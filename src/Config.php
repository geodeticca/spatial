<?php
/**
 * User: Maros Jasan
 * Date: 12. 10. 2020
 * Time: 11:23
 */

namespace Geodeticca\Spatial;

class Config
{
    /**
     * @var array
     */
    private static $config = [];

    /**
     * @var \Geodeticca\Spatial\Config
     */
    private static $instance;

    /**
     * Connection constructor.
     */
    private function __construct()
    {
    }

    /**
     * Connection clone.
     */
    private function __clone()
    {
    }

    /**
     * @param array $config
     * @return void
     */
    public static function configure(array $config): void
    {
        self::$config = $config;
    }

    /**
     * @return \Geodeticca\Spatial\Config
     */
    public static function instance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        $config = self::$config;

        if (strpos($key, '.') !== false) {
            $indexes = explode('.', $key);

            foreach ($indexes as $index) {
                if (array_key_exists($index, $config)) {
                    $config = $config[$index];
                }
            }

            return $config;
        } else {
            if (array_key_exists($key, $config)) {
                return $config[$key];
            }
        }
    }
}
