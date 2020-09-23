<?php
/**
 * User: Maros Jasan
 * Date: 29.9.2017
 * Time: 15:49
 */

namespace Geodeticca\Spatial;

abstract class Command
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var array
     */
    protected $params = [];

    const DEST_TYPE_FILE = 'file';
    const DEST_TYPE_DIR = 'dir';

    /**
     * Command constructor.
     * @param string $source
     * @param string|null $destination
     * @param string|null $destType
     * @return void
     */
    public function __construct(string $source, string $destination = null, string $destType = null)
    {
        $this->source = $source;

        if ($destination) {
            $this->setDestination($destination, $destType);
        }

        $this->setDefaultParams();
    }

    /**
     * @param string $destination
     * @param string|null $destType
     * @return $this
     */
    protected function setDestination(string $destination, string $destType = null) : self
    {
        switch (strtolower($destType)) {
            default:
            case self::DEST_TYPE_FILE:
                $destDir = dirname($destination);

                break;

            case self::DEST_TYPE_DIR:
                $destDir = $destination;

                break;
        }

        if (!file_exists($destDir)) {
            mkdir($destDir, 0777, true);
        }

        $this->destination = $destination;

        return $this;
    }

    /**
     * @return $this
     */
    abstract protected function setDefaultParams();

    /**
     * @param string $value
     * @return $this
     */
    public function addParam(string $value) : self
    {
        $this->params[] = $value;

        return $this;
    }

    /**
     * @return string
     */
    protected function buildParams() : string
    {
        $paramsOptions = $this->params;

        $paramsOptions[] = $this->source;
        if ($this->destination) {
            $paramsOptions[] = $this->destination;
        }

        return trim(implode(' ', $paramsOptions));
    }

    /**
     * @return string
     */
    protected function buildCommand() : string
    {
        return $this->command . ' ' . $this->buildParams();
    }

    /**
     * @return bool
     */
    public function execute() : bool
    {
        $command = $this->buildCommand();

        $return = null;
        $output = [];
        exec($command, $output, $return);

        return ($return === 0);
    }

    /**
     * @return void
     */
    public function debug() : void
    {
        $command = $this->buildCommand();

        echo $command;
        exit();
    }
}
