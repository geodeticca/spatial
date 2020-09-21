<?php
/**
 * User: Maros Jasan
 * Date: 18.10.2017
 * Time: 12:38
 */

namespace Geodeticca\Spatial;

class Tiles extends Command
{
    protected $command = 'gdal2tiles.py';

    protected function setDefaultParams()
    {
        $this->params = [
            '-w none',
            '-z 10-20',
            '-p mercator',
            '--processes 2'
        ];
    }
}
