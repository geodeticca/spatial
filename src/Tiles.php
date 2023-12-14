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
    protected function setDefaultParams(): self
    {
        $this
            ->addParam('-w none')
            ->addParam('-p mercator');

        return $this;
    }

    /**
     * @return string
     */
    protected function buildExecutable(): string
    {
        $executable = parent::buildExecutable();

        return 'python3 ' . $executable;
    }
}
