<?php

namespace SafeAlternative\Sameday\Objects\Service;

use SafeAlternative\Sameday\Objects\Traits\SamedayObjectCodeTrait;
use SafeAlternative\Sameday\Objects\Traits\SamedayObjectIdTrait;
use SafeAlternative\Sameday\Objects\Traits\SamedayObjectNameTrait;
use SafeAlternative\Sameday\Objects\Types\CostType;
use SafeAlternative\Sameday\Objects\Types\PackageType;
/**
 * Optional tax for service.
 *
 * @package Sameday\Objects\Service
 */
class OptionalTaxObject
{
    use SamedayObjectIdTrait;
    use SamedayObjectNameTrait;
    use SamedayObjectCodeTrait;
    /**
     * @var CostType
     */
    protected $costType;
    /**
     * @var float
     */
    protected $tax;
    /**
     * @var PackageType
     */
    protected $packageType;
    /**
     * OptionalTaxObject constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $code
     * @param CostType $costType
     * @param float $tax
     * @param PackageType $packageType
     */
    public function __construct($id, $name, $code, CostType $costType, $tax, PackageType $packageType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
        $this->costType = $costType;
        $this->tax = $tax;
        $this->packageType = $packageType;
    }
    /**
     * @return CostType
     */
    public function getCostType()
    {
        return $this->costType;
    }
    /**
     * @return float
     */
    public function getTax()
    {
        return $this->tax;
    }
    /**
     * @return PackageType
     */
    public function getPackageType()
    {
        return $this->packageType;
    }
}
