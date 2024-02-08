<?php
/**
 * User: Maros Jasan
 * Date: 29.9.2017
 * Time: 15:49
 */

namespace Geodeticca\Spatial;

use Geodeticca\Spatial\Config as SpatialConfig;

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
    protected $multiParams = [];

    /**
     * @var array
     */
    protected $suffixes = [];

    /**
     * @var \Geodeticca\Spatial\Config
     */
    protected $config;

    /**
     * Command constructor.
     * @param string $source
     * @param string|null $destination
     */
    public function __construct(string $source, string $destination = null)
    {
        $this->configure();

        $this->setSource($source);

        if ($destination) {
            $this->setDestination($destination);
        }

        $this->setDefaultParams();
    }

    /**
     * @return $this
     */
    protected function configure()
    {
        $this->config = SpatialConfig::instance();
        $this->config->configure([
            'multiparams' => ['--config', '-wo', '-co'],
        ]);

        return $this;
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
    public function setDestination(string $destination): self
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
     * @param string $param
     * @return bool
     */
    public function isMultiParam(string $param): bool
    {
        return in_array($param, $this->config->get('multiparams'));
    }

    /**
     * @param string $param
     * @return $this
     */
    public function addParam(string $param): self
    {
        $param = trim($param);
        
        if (strpos($param, ' ') !== false) {
            list($key, $value) = explode(' ', $param, 2);
        } else {
            $key = $param;
            $value = '';
        }

        if ($this->isMultiParam($key)) {
            $this->multiParams[] = $param;
        } else {
            $this->params[$key] = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    protected function buildParams(): string
    {
        $params = $this->params;
        $paramsKeys = array_keys($params);

        $multiParams = trim(implode(' ', $this->multiParams));

        $params = trim(implode(' ', array_map(function ($paramKey) use ($params) {
            $paramValue = $params[$paramKey];

            return trim($paramKey . ' ' . $paramValue);
        }, $paramsKeys)));

        return $multiParams . ' ' . $params;
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
        $src = '"' . dirname($this->source) . '"' . DIRECTORY_SEPARATOR . basename($this->source);
        $dest = '"' . dirname($this->destination) . '"' . DIRECTORY_SEPARATOR . basename($this->destination);

        if ($this->srcDestOrder === 'STANDARD'){
            $srcDestOptions = [
                $src,
            ];

            if (!is_null($this->destination)) {
                $srcDestOptions = array_merge($srcDestOptions, [
                    $dest,
                ]);
            }
        } else {
            if (is_null($this->destination)) {
                throw new \Exception('Destination parameter cannot be null.');
            }

            $srcDestOptions = [
                $dest,
            ];

            $srcDestOptions = array_merge($srcDestOptions, [
                $src,
            ]);
        }

        return trim(implode(' ', $srcDestOptions));
    }

    /**
     * @return string
     */
    protected function buildExecutable(): string
    {
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
