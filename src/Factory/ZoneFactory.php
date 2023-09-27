<?php

namespace App\Factory;

use App\Entity\Zone;
use App\Repository\ZoneRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Zone>
 *
 * @method        Zone|Proxy create(array|callable $attributes = [])
 * @method static Zone|Proxy createOne(array $attributes = [])
 * @method static Zone|Proxy find(object|array|mixed $criteria)
 * @method static Zone|Proxy findOrCreate(array $attributes)
 * @method static Zone|Proxy first(string $sortedField = 'id')
 * @method static Zone|Proxy last(string $sortedField = 'id')
 * @method static Zone|Proxy random(array $attributes = [])
 * @method static Zone|Proxy randomOrCreate(array $attributes = [])
 * @method static ZoneRepository|RepositoryProxy repository()
 * @method static Zone[]|Proxy[] all()
 * @method static Zone[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Zone[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Zone[]|Proxy[] findBy(array $attributes)
 * @method static Zone[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Zone[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ZoneFactory extends ModelFactory
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
            //'event' => EventFactory::new(),
            'name' => self::faker()->words(3, true),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Zone $zone): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Zone::class;
    }
}
