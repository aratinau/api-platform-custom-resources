<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     shortName="daily-stats"
 *  )
 */
class DailyStats
{
    public $date;

    public $totalVisitors;

    public $mostPopularListings;
}
