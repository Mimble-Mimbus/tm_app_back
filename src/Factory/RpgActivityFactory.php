<?php

namespace App\Factory;

use App\Entity\RpgActivity;
use App\Repository\RpgActivityRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RpgActivity>
 *
 * @method        RpgActivity|Proxy create(array|callable $attributes = [])
 * @method static RpgActivity|Proxy createOne(array $attributes = [])
 * @method static RpgActivity|Proxy find(object|array|mixed $criteria)
 * @method static RpgActivity|Proxy findOrCreate(array $attributes)
 * @method static RpgActivity|Proxy first(string $sortedField = 'id')
 * @method static RpgActivity|Proxy last(string $sortedField = 'id')
 * @method static RpgActivity|Proxy random(array $attributes = [])
 * @method static RpgActivity|Proxy randomOrCreate(array $attributes = [])
 * @method static RpgActivityRepository|RepositoryProxy repository()
 * @method static RpgActivity[]|Proxy[] all()
 * @method static RpgActivity[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static RpgActivity[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static RpgActivity[]|Proxy[] findBy(array $attributes)
 * @method static RpgActivity[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RpgActivity[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RpgActivityFactory extends ModelFactory
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
            'duration' => self::faker()->numberBetween(3, 5),
            'isCanceled' => self::faker()->boolean(),
            'maxNumberSeats' => self::faker()->numberBetween(3, 5),
            'name' => self::faker()->unique()->words(5, true),
            'onReservation' => self::faker()->boolean(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(RpgActivity $rpgActivity): void {})
        ;
    }

    protected static function getClass(): string
    {
        return RpgActivity::class;
    }
}
