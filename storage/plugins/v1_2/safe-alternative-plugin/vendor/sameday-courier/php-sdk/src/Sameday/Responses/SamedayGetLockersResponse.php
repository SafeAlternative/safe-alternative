<?php

namespace SafeAlternative\Sameday\Responses;

use SafeAlternative\Sameday\Http\SamedayRawResponse;
use SafeAlternative\Sameday\Objects\Locker\BoxObject;
use SafeAlternative\Sameday\Objects\Locker\LockerObject;
use SafeAlternative\Sameday\Objects\Locker\ScheduleObject;
use SafeAlternative\Sameday\Requests\SamedayGetLockersRequest;
use SafeAlternative\Sameday\Responses\Traits\SamedayResponseTrait;
/**
 * Response for get lockers request.
 *
 * @package Sameday
 */
class SamedayGetLockersResponse implements SamedayResponseInterface
{
    use SamedayResponseTrait;
    /**
     * @var LockerObject[]
     */
    protected $lockers = [];
    /**
     * SamedayGetLockersResponse constructor.
     *
     * @param SamedayGetLockersRequest $request
     * @param SamedayRawResponse $rawResponse
     */
    public function __construct(SamedayGetLockersRequest $request, SamedayRawResponse $rawResponse)
    {
        $this->request = $request;
        $this->rawResponse = $rawResponse;
        $json = \json_decode($this->rawResponse->getBody(), \true);
        if (!$json) {
            // Empty response.
            return;
        }
        foreach ($json as $locker) {
            $this->lockers[] = new LockerObject($locker['lockerId'], $locker['name'], $locker['county'], $locker['city'], $locker['address'], $locker['postalCode'], $locker['lat'], $locker['lng'], $locker['phone'], $locker['email'], \array_map(function ($entry) {
                return new BoxObject($entry['size'], $entry['number']);
            }, $locker['availableBoxes']), \array_map(function ($entry) {
                return new ScheduleObject($entry['day'], $entry['openingHour'], $entry['closingHour']);
            }, $locker['schedule']));
        }
    }
    /**
     * @return LockerObject[]
     */
    public function getLockers()
    {
        return $this->lockers;
    }
}