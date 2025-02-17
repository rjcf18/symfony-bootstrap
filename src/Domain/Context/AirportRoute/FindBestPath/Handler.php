<?php declare(strict_types=1);
namespace App\Domain\Context\AirportRoute\FindBestPath;

use App\Domain\Context\AirportRoute\FindBestPath\Exception\DestinationAirportNotFoundException;
use App\Domain\Context\AirportRoute\FindBestPath\Exception\OriginAirportNotFoundException;

interface Handler
{
    /**
     * @param RequestModel $useCaseRequest
     *
     * @throws DestinationAirportNotFoundException
     * @throws OriginAirportNotFoundException
     *
     * @return ResponseModel
     */
    public function findBestRoute(RequestModel $useCaseRequest): ResponseModel;
}