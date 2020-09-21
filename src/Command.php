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
     * @param string $destination
     * @param string $destType
     * @return void
     */
    public function __construct($source, $destination = null, $destType = null)
    {
        $this->source = $source;

        if ($destination) {
            $this->setDestination($destination, $destType);
        }

        $this->setDefaultParams();
    }

    /**
     * @param string $destination
     * @param string $destType
     * @return $this
     */
    protected function setDestination($destination, $destType = self::DEST_TYPE_FILE)
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

    abstract protected function setDefaultParams();

    /**
     * @param string $value
     * @return $this
     */
    public function addParam($value)
    {
        $this->params[] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function build()
    {
        $paramsOptions = $this->params;

        $paramsOptions[] = $this->source;
        if ($this->destination) {
            $paramsOptions[] = $this->destination;
        }

        return trim(implode(' ', $paramsOptions));
    }

    /**
     * @param bool $debug
     * @return bool
     */
    public function execute($debug = false)
    {
        $command = $this->command . ' ' . $this->build();

        if ($debug === true) {
            echo $command;
            exit();
        }

        $return = null;
        $output = [];
        exec($command, $output, $return);

        return ($return === 0);
    }
}
