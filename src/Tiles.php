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
     * @return string
     */
    protected function getDestType(): string
    {
        return self::DEST_TYPE_DIR;
    }

    /**
     * @return $this
     */
    protected function setDefaultParams(): self
    {
        $this->params = [
            '-w none',
            '-z 10-20',
            '-p mercator',
            '--processes 2'
        ];

        return $this;
    }

    /**
     * @return string
     */
    protected function buildExecutable(): string
    {
        $executable = trim($this->getExecutable() . DIRECTORY_SEPARATOR . $this->command, DIRECTORY_SEPARATOR);

        return 'python ' . $executable;
    }
}
