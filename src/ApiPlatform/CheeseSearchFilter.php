<?php

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

// NOTE AbstractFilter works only with Entity instead of FilterInterface on DailyStatsDateFilter class
class CheeseSearchFilter extends AbstractFilter
{
    private $useLike;

    public function __construct(ManagerRegistry $managerRegistry, bool $useLike = false, ?RequestStack $requestStack = null, NameConverterInterface $nameConverter = null)
    {
        parent::__construct($managerRegistry, $requestStack, null,null, $nameConverter);
        $this->useLike = $useLike;
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== 'search') {
            return;
        }

        $alias = $queryBuilder->getRootAliases()[0];
        // a param name that is guaranteed unique in this query
        $valueParameter = $queryNameGenerator->generateParameterName('search');
        $queryBuilder->andWhere(sprintf('%s.title LIKE :%s OR %s.description LIKE :%s', $alias, $valueParameter, $alias, $valueParameter))
            ->setParameter($valueParameter, '%'.$value.'%');
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'search' => [
                'property' => null,
                'type' => 'string',
                'required' => false,
                'openapi' => [
                    'description' => 'Search across multiple fields',
                ],
            ]
        ];
    }
}
