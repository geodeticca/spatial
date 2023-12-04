<?php
/**
 * User: Maros Jasan
 * Date: 29.9.2017
 * Time: 16:25
 */

namespace Geodeticca\Spatial;

class Warp extends Command
{
    /**
     * @var string
     */
    protected $command = 'gdalwarp';

    /**
     * @return $this
     */
    protected function setDefaultParams(): self
    {
        $this->params = [
            '--config GDAL_CACHEMAX 2048', // sprava cache
            '--config GDAL_DISABLE_READDIR_ON_OPEN TRUE', // zakaz nacitania celeho adresara
            '-wm 4096', // sprava pamate
            '-multi', // multi processing
            '-wo NUM_THREADS=4', // pocet vlakien vstupujucich do warp algoritmu
            '-co NUM_THREADS=4', // pocet vlakien vstupujucich do kompresneho algoritmu
            '-overwrite' // overwite file if exists
        ];

        return $this;
    }
}
