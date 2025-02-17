<?php declare(strict_types=1);
namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Context\AirportRoute\FindBestPath\Collection\PossibleRoutesCollection;
use App\Domain\Context\AirportRoute\FindBestPath\RouteRepository as FindBestPathRouteRepository;
use App\Domain\Shared\Entity\Route;
use App\Domain\Shared\Entity\Route as RouteEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RouteRepository extends ServiceEntityRepository implements FindBestPathRouteRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteEntity::class);
    }

    public function fetchAirportsPossibleRoutes(): PossibleRoutesCollection
    {
        /** @var Route[] $routes */
        $routes = $this->findAll();

        $possibleRoutes = PossibleRoutesCollection::create();

        foreach ($routes as $route) {
            $possibleRoutes->addPossibleRoute($route->getOrigin(), $route->getDestiny());
        }

        return $possibleRoutes;
    }
}