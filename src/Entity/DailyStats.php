<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "controller"="NotFoundAction::class",
 *              "read"=false,
 *              "output"=false
 *          },
 *     },
 *     collectionOperations={"get"}
 *  )
 */
class DailyStats
{
    public $date;

    public $totalVisitors;

    public $mostPopularListings;

    /**
     * // @ApiProperty(identifier=true)
     */
    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
