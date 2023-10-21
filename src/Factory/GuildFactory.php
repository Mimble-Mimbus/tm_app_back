<?php

namespace App\Factory;

use App\Entity\Guild;
use App\Repository\GuildRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Guild>
 *
 * @method        Guild|Proxy create(array|callable $attributes = [])
 * @method static Guild|Proxy createOne(array $attributes = [])
 * @method static Guild|Proxy find(object|array|mixed $criteria)
 * @method static Guild|Proxy findOrCreate(array $attributes)
 * @method static Guild|Proxy first(string $sortedField = 'id')
 * @method static Guild|Proxy last(string $sortedField = 'id')
 * @method static Guild|Proxy random(array $attributes = [])
 * @method static Guild|Proxy randomOrCreate(array $attributes = [])
 * @method static GuildRepository|RepositoryProxy repository()
 * @method static Guild[]|Proxy[] all()
 * @method static Guild[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Guild[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Guild[]|Proxy[] findBy(array $attributes)
 * @method static Guild[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Guild[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class GuildFactory extends ModelFactory
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
            //'event' => EventFactory::new(),
            'name' => 'Guilde '. self::faker()->unique()->word(),
            'points' => self::faker()->numberBetween(0, 1000),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Guild $guild): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Guild::class;
    }
}
