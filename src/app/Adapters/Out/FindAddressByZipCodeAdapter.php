<?php

namespace App\Adapters\Out;

use App\Application\Core\Domain\Address;
use App\Application\Ports\Out\FindAddressByZipCodeOutputPort;

class FindAddressByZipCodeAdapter implements FindAddressByZipCodeOutputPort
{
    private $findAddressByZipCodeClient;
    private $addressResponseMapper;

    public function find(string $zipCode): Address
    {
        $addressResponse = $this->findAddressByZipCodeClient->find($zipCode);
        return $this->addressResponseMapper->toAddress($addressResponse);
    }
}
