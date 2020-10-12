<?php
/**
 * User: Maros Jasan
 * Date: 16.10.2017
 * Time: 14:54
 */

namespace Geodeticca\Spatial;

class Info extends Command
{
    /**
     * @var string
     */
    protected $command = 'gdalinfo';

    /**
     * @return $this
     */
    protected function setDefaultParams(): self
    {
        $this->params = [
            '-json', // display the output in json format
        ];

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function execute()
    {
        $command = $this->buildCommand();

        $return = null;
        $output = [];
        exec($command, $output, $return);

        $result = implode(null, $output);
        $result = str_replace(['\n'], [null], $result);

        return json_decode($result);
    }
}
