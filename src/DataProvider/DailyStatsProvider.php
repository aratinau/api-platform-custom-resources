<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\DailyStats;
use App\Repository\CheeseListingRepository;

class DailyStatsProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $cheeseListingRepository;

    public function __construct(CheeseListingRepository $cheeseListingRepository)
    {
        $this->cheeseListingRepository = $cheeseListingRepository;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $listings = $this->cheeseListingRepository
            ->findBy([], [], 5);

        $stats = new DailyStats(
            new \DateTime(),
            1000,
            $listings
        );

        $stats2 = new DailyStats(
            new \DateTime('-1 days'),
            2000,
            $listings
        );

        return [$stats, $stats2];
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === DailyStats::class;
    }

}
