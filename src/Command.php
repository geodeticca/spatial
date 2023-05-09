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
     * @var string
     */
    protected $srcDestOrder = 'STANDARD';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $suffixes = [];

    /**
     * Command constructor.
     * @param string $source
     * @param string|null $destination
     */
    public function __construct(string $source, string $destination = null)
    {
        if ($this->srcDestOrder === 'STANDARD') {
            $this->setSource($source);

            if ($destination) {
                $this->setDestination($destination);
            }
        }

        $this->setDefaultParams();
    }

    /**
     * @return string|null
     */
    protected function getExecutable()
    {
        $config = Config::instance();

        return $config::get('gdal.executable');
    }

    /**
     * @param string $source
     * @return $this
     */
    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @param string $destination
     * @return $this
     */
    protected function setDestination(string $destination): self
    {
        $filename = basename($destination);

        if (is_filename($filename)) {
            $destDir = dirname($destination);
        } else {
            $destDir = $destination;
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
    public function addParam(string $value): self
    {
        $this->params[] = $value;

        return $this;
    }

    /**
     * @return string
     */
    protected function buildParams(): string
    {
        return trim(implode(' ', $this->params));
    }

    /**
     * @param string $value
     * @return $this
     */
    public function addSuffix(string $value): self
    {
        $this->suffixes[] = $value;

        return $this;
    }

    /**
     * @return string
     */
    protected function buildSuffixes(): string
    {
        return trim(implode(' ', $this->suffixes));
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function buildSrcDest(): string
    {
        if ($this->srcDestOrder === 'STANDARD'){
            $srcDestOptions = [
                '"' . $this->source . '"',
            ];

            if (!is_null($this->destination)) {
                $srcDestOptions = array_merge($srcDestOptions, [
                    '"' . $this->destination . '"',
                ]);
            }
        } else {
            if (is_null($this->destination)) {
                throw new \Exception('Destination parameter cannot be null.');
            }

            $srcDestOptions = [
                '"' . $this->destination . '"',
            ];

            $srcDestOptions = array_merge($srcDestOptions, [
                '"' . $this->source . '"',
            ]);
        }

        return trim(implode(' ', $srcDestOptions));
    }

    /**
     * @return string
     */
    protected function buildExecutable(): string
    {
        //return rtrim($this->getExecutable() . DIRECTORY_SEPARATOR . $this->command, DIRECTORY_SEPARATOR);
        return $this->command;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function buildCommand(): string
    {
        return implode(' ', [
            $this->buildExecutable(),
            $this->buildParams(),
            $this->buildSrcDest(),
            $this->buildSuffixes(),
        ]);
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

        return ($return === 0);
    }

    /**
     * @throws \Exception
     */
    public function debug(): void
    {
        $command = $this->buildCommand();

        echo $command;
        exit();
    }
}
