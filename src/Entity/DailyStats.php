<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"daily-stats:read"}},
 *     paginationItemsPerPage=7,
 *     itemOperations={
 *          "get"
 *     },
 *     collectionOperations={"get"}
 *  )
 */
class DailyStats
{
    /**
     * @Groups({"daily-stats:read"})
     */
    public $date;

    /**
     * @Groups({"daily-stats:read"})
     */
    public $totalVisitors;

    /**
     * The 5 most popular cheese listings from this date!
     *
     * @var array<CheeseListing>|CheeseListing[]
     * @Groups({"daily-stats:read"})
     */
    public $mostPopularListings;

    /* NOTE
     * @var array<CheeseListing>
     *
        mostPopularListings
            @id	"/api/cheeses/1"
            @type	"cheese"

            @id	"/api/cheeses/2"
            @type	"cheese"

            ...
        TO
        mostPopularListings
            "/api/cheeses/1"
            "/api/cheeses/2"
            ...
     *
     * */

    /**
     * @param array|CheeseListing[] $mostPopularListings
     */
    public function __construct(\DateTimeInterface $date, int $totalVisitors, array $mostPopularListings)
    {
        $this->date = $date;
        $this->totalVisitors = $totalVisitors;
        $this->mostPopularListings = $mostPopularListings;
    }

    /**
     * // @ApiProperty(identifier=true)
     */
    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }
}
