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
        $this
            ->addParam('--config GDAL_CACHEMAX 2048') // cache management
            ->addParam('--config GDAL_DISABLE_READDIR_ON_OPEN TRUE') // zakaz nacitania celeho adresara
            ->addParam('-wm 4096') // pocet vlakien vstupujucich do kompresneho algoritmu
            ->addParam('-multi') // multi processing
            ->addParam('-wo NUM_THREADS=4') // pocet vlakien vstupujucich do warp algoritmu
            ->addParam('-co NUM_THREADS=4') // pocet vlakien vstupujucich do kompresneho algoritmu
            ->addParam('-overwrite'); // overwite file if exists

        return $this;
    }
}
