<?php
/**
 * User: Maros Jasan
 * Date: 29.9.2017
 * Time: 16:25
 */

namespace Geodeticca\Spatial;

class Translate extends Command
{
    /**
     * @var string
     */
    protected $command = 'gdal_translate';

    /**
     * @return $this
     */
    protected function setDefaultParams(): self
    {
        $this->params = [];

        return $this;
    }

    /**
     * @param array $set1
     * @param array $set2
     * @return $this
     */
    public function addGCPs(array $set1, array $set2): self
    {
        $set1Count = count($set1);
        $set2Count = count($set2);

        // ak je jedno pole bodov mensie ako druhe, tak obe polia su orezane podla mensieho
        $pointsCount = min($set1Count, $set2Count);
        if ($set1Count !== $set2Count) {
            $set1 = array_slice($set1, 0, $pointsCount);
            $set2 = array_slice($set2, 0, $pointsCount);
        }

        for ($i = 0; $i < $pointsCount; $i++) {
            $set1Point = $set1[$i];
            $set2Point = $set2[$i];

            $gcp = $set2Point[0] . ' ' . $set2Point[1] . ' ' . $set1Point[0] . ' ' . $set1Point[1];

            $this->addParam('-gcp ' . $gcp);
        }

        return $this;
    }
}
