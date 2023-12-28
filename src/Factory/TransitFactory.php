<?php

namespace App\Factory;

use App\Entity\Transit;
use App\Repository\TransitRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Transit>
 *
 * @method        Transit|Proxy                     create(array|callable $attributes = [])
 * @method static Transit|Proxy                     createOne(array $attributes = [])
 * @method static Transit|Proxy                     find(object|array|mixed $criteria)
 * @method static Transit|Proxy                     findOrCreate(array $attributes)
 * @method static Transit|Proxy                     first(string $sortedField = 'id')
 * @method static Transit|Proxy                     last(string $sortedField = 'id')
 * @method static Transit|Proxy                     random(array $attributes = [])
 * @method static Transit|Proxy                     randomOrCreate(array $attributes = [])
 * @method static TransitRepository|RepositoryProxy repository()
 * @method static Transit[]|Proxy[]                 all()
 * @method static Transit[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Transit[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Transit[]|Proxy[]                 findBy(array $attributes)
 * @method static Transit[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Transit[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class TransitFactory extends ModelFactory
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
            'address' => self::faker()->address(),
            'arrival' => self::faker()->time(),
            'availableSeats' => self::faker()->randomNumber(2),
            'event' => EventFactory::new(),
            'name' => self::faker()->text(255),
            'start' => self::faker()->time(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Transit $transit): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Transit::class;
    }
}
