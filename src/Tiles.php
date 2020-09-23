<?php
/**
 * User: Maros Jasan
 * Date: 18.10.2017
 * Time: 12:38
 */

namespace Geodeticca\Spatial;

class Tiles extends Command
{
    /**
     * @var string
     */
    protected $command = 'gdal2tiles.py';

    /**
     * @return $this
     */
    protected function setDefaultParams()
    {
        $this->params = [
            '-w none',
            '-z 10-20',
            '-p mercator',
            '--processes 2'
        ];

        return $this;
    }
}
