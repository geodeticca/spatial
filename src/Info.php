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
     * @var array
     */
    protected $result = [];

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
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function execute(): bool
    {
        $command = $this->buildCommand();

        $return = null;
        $output = [];
        exec($command, $output, $return);

        if (!empty($output)) {
            $result = implode(null, $output);
            $result = str_replace(['\n'], [null], $result);

            $this->result = json_decode($result);
        }

        return ($return === 0);
    }
}
