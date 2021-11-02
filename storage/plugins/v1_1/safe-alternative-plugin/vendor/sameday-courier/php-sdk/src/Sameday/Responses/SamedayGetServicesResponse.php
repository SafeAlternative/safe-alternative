<?php

namespace SafeAlternative\Sameday\Responses;

use SafeAlternative\Sameday\Http\SamedayRawResponse;
use SafeAlternative\Sameday\Objects\Service\DeliveryTypeObject;
use SafeAlternative\Sameday\Objects\Service\OptionalTaxObject;
use SafeAlternative\Sameday\Objects\Service\ServiceObject;
use SafeAlternative\Sameday\Objects\Types\CostType;
use SafeAlternative\Sameday\Objects\Types\PackageType;
use SafeAlternative\Sameday\Requests\SamedayGetServicesRequest;
use SafeAlternative\Sameday\Responses\Traits\SamedayResponsePaginationTrait;
use SafeAlternative\Sameday\Responses\Traits\SamedayResponseTrait;
/**
 * Response for get services request.
 *
 * @package Sameday
 */
class SamedayGetServicesResponse implements SamedayPaginatedResponseInterface
{
    use SamedayResponsePaginationTrait;
    use SamedayResponseTrait;
    /**
     * @var ServiceObject[]
     */
    protected $services = [];
    /**
     * SamedayGetServicesResponse constructor.
     *
     * @param SamedayGetServicesRequest $request
     * @param SamedayRawResponse $rawResponse
     */
    public function __construct(SamedayGetServicesRequest $request, SamedayRawResponse $rawResponse)
    {
        $this->request = $request;
        $this->rawResponse = $rawResponse;
        $json = \json_decode($this->rawResponse->getBody(), \true);
        $this->parsePagination($this->request, $json);
        if (!$json) {
            // Empty response.
            return;
        }
        foreach ($json['data'] as $data) {
            $this->services[] = new ServiceObject($data['id'], $data['name'], $data['serviceCode'], new DeliveryTypeObject($data['deliveryType']['id'], $data['deliveryType']['name']), $data['defaultServices'], \array_map(function ($entry) {
                return new OptionalTaxObject($entry['id'], $entry['name'], isset($entry['taxCode']) ? $entry['taxCode'] : '', new CostType($entry['costType']), $entry['tax'], new PackageType($entry['packageType']));
            }, $data['serviceOptionalTaxes']));
        }
    }
    /**
     * @return ServiceObject[]
     */
    public function getServices()
    {
        return $this->services;
    }
}
