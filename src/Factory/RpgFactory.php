<?php

namespace App\Factory;

use App\Entity\Rpg;
use App\Repository\RpgRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Rpg>
 *
 * @method        Rpg|Proxy create(array|callable $attributes = [])
 * @method static Rpg|Proxy createOne(array $attributes = [])
 * @method static Rpg|Proxy find(object|array|mixed $criteria)
 * @method static Rpg|Proxy findOrCreate(array $attributes)
 * @method static Rpg|Proxy first(string $sortedField = 'id')
 * @method static Rpg|Proxy last(string $sortedField = 'id')
 * @method static Rpg|Proxy random(array $attributes = [])
 * @method static Rpg|Proxy randomOrCreate(array $attributes = [])
 * @method static RpgRepository|RepositoryProxy repository()
 * @method static Rpg[]|Proxy[] all()
 * @method static Rpg[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Rpg[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Rpg[]|Proxy[] findBy(array $attributes)
 * @method static Rpg[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Rpg[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RpgFactory extends ModelFactory
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
            'name' => self::faker()->words(5, true),
            'publisher' => self::faker()->words(2, true),
            'universe' => self::faker()->words(5, true),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Rpg $rpg): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Rpg::class;
    }
}
