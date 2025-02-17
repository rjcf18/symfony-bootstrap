<?php declare(strict_types=1);
namespace App\Domain\Context\AirportRoute\FindBestPath;

use App\Domain\Context\AirportRoute\FindBestPath\Exception\DestinationAirportNotFoundException;
use App\Domain\Context\AirportRoute\FindBestPath\Exception\OriginAirportNotFoundException;
use App\Domain\Context\AirportRoute\FindBestPath\Validator\Semantic as SemanticValidator;

class Service implements Handler
{
    private SemanticValidator $semanticValidator;

    private AirportRepository $airportRepository;

    private RouteRepository $routeRepository;

    private ShortestPathFinder $shortestPathFinder;

    public function __construct(
        SemanticValidator $semanticValidator,
        AirportRepository $airportRepository,
        RouteRepository $routeRepository,
        ShortestPathFinder $shortestPathFinder
    ) {
        $this->semanticValidator = $semanticValidator;
        $this->airportRepository = $airportRepository;
        $this->routeRepository = $routeRepository;
        $this->shortestPathFinder = $shortestPathFinder;
    }

    /**
     * @param RequestModel $useCaseRequest
     *
     * @throws DestinationAirportNotFoundException
     * @throws OriginAirportNotFoundException
     *
     * @return ResponseModel
     */
    public function findBestRoute(RequestModel $useCaseRequest): ResponseModel
    {
        $originAirport = $this->airportRepository->getById($useCaseRequest->getOriginAirportId());
        $destinationAirport = $this->airportRepository->getById($useCaseRequest->getDestinationAirportId());

        $this->semanticValidator->validate($originAirport, $destinationAirport);

        $airportsPossibleRoutes = $this->routeRepository->fetchAirportsPossibleRoutes();

        $airportCollection = $this->shortestPathFinder->findShortestPath(
            $airportsPossibleRoutes,
            $originAirport,
            $destinationAirport
        );

        return new ResponseModel($airportCollection);
    }
}