<?php

namespace App\Factory;

use App\Entity\Entertainment;
use App\Repository\EntertainmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Entertainment>
 *
 * @method        Entertainment|Proxy create(array|callable $attributes = [])
 * @method static Entertainment|Proxy createOne(array $attributes = [])
 * @method static Entertainment|Proxy find(object|array|mixed $criteria)
 * @method static Entertainment|Proxy findOrCreate(array $attributes)
 * @method static Entertainment|Proxy first(string $sortedField = 'id')
 * @method static Entertainment|Proxy last(string $sortedField = 'id')
 * @method static Entertainment|Proxy random(array $attributes = [])
 * @method static Entertainment|Proxy randomOrCreate(array $attributes = [])
 * @method static EntertainmentRepository|RepositoryProxy repository()
 * @method static Entertainment[]|Proxy[] all()
 * @method static Entertainment[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Entertainment[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Entertainment[]|Proxy[] findBy(array $attributes)
 * @method static Entertainment[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Entertainment[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class EntertainmentFactory extends ModelFactory
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
            'description' => self::faker()->text(),
            'duration' => self::faker()->numberBetween(1, 4),
            //'entertainmentType' => EntertainmentTypeFactory::new(),
            'isCanceled' => self::faker()->boolean(),
            'maxNumberSeats' => self::faker()->numberBetween(2, 50),
            'name' => self::faker()->unique()->words(rand(1, 6), true),
            'onReservation' => self::faker()->boolean(),
            //'zone' => ZoneFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Entertainment $entertainment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Entertainment::class;
    }
}
