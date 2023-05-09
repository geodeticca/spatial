<?php
/**
 * User: Maros Jasan
 * Date: 4. 2. 2021
 * Time: 15:29
 */

namespace Geodeticca\Spatial;

class TileIndex extends Command
{
    /**
     * @var string
     */
    protected $command = 'gdaltindex';

    /**
     * @var string
     */
    protected $srcDestOrder = 'REVERSE';

    /**
     * @return $this
     */
    protected function setDefaultParams(): self
    {
        $this->params = [
            '-lyr_name index', // default layer name is index
        ];

        return $this;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $command = $this->buildCommand();

        $return = null;
        $output = [];
        exec($command, $output, $return);

        return ($return === 0);
    }
}
