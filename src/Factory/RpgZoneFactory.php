<?php

namespace App\Factory;

use App\Entity\RpgZone;
use App\Repository\RpgZoneRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RpgZone>
 *
 * @method        RpgZone|Proxy create(array|callable $attributes = [])
 * @method static RpgZone|Proxy createOne(array $attributes = [])
 * @method static RpgZone|Proxy find(object|array|mixed $criteria)
 * @method static RpgZone|Proxy findOrCreate(array $attributes)
 * @method static RpgZone|Proxy first(string $sortedField = 'id')
 * @method static RpgZone|Proxy last(string $sortedField = 'id')
 * @method static RpgZone|Proxy random(array $attributes = [])
 * @method static RpgZone|Proxy randomOrCreate(array $attributes = [])
 * @method static RpgZoneRepository|RepositoryProxy repository()
 * @method static RpgZone[]|Proxy[] all()
 * @method static RpgZone[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static RpgZone[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static RpgZone[]|Proxy[] findBy(array $attributes)
 * @method static RpgZone[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RpgZone[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RpgZoneFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'MaxAvailableSeatsPerTable' => 5,
            'MaxEndHour' => '18h',
            'MinStartHour' => '9h',
            'availableTables' => 4,
            //'event' => EventFactory::new(),
            'name' => "Zone de JDR",
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(RpgZone $rpgZone): void {})
        ;
    }

    protected static function getClass(): string
    {
        return RpgZone::class;
    }
}
