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
    protected function setDefaultParams()
    {
        $this->params = [
            '-json', // display the output in json format
        ];

        return $this;
    }

    /**
     * @param bool $debug
     * @return \stdClass
     */
    public function execute($debug = false)
    {
        $command = $this->command . ' ' . $this->build();

        if ($debug === true) {
            echo $command;
            exit;
        }

        $return = null;
        $output = [];
        exec($command, $output, $return);

        $result = implode(null, $output);
        $result = str_replace(['\n'], [null], $result);

        return json_decode($result);
    }
}
