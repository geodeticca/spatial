<?php
/**
 * User: Maros Jasan
 * Date: 29.9.2017
 * Time: 16:25
 */

namespace Geodeticca\Spatial;

class Gdalbuildvrt extends Command
{
    /**
     * @var string
     */
    protected $command = 'gdalbuildvrt';

    /**
     * @var string
     */
    protected $srcDestOrder = 'REVERSE';

    /**
     * @return $this
     */
    protected function setDefaultParams(): self
    {
        $this->params = [];

        return $this;
    }
}
