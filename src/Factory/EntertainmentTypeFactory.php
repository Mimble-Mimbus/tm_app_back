<?php

namespace App\Factory;

use App\Entity\EntertainmentType;
use App\Repository\EntertainmentTypeRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<EntertainmentType>
 *
 * @method        EntertainmentType|Proxy create(array|callable $attributes = [])
 * @method static EntertainmentType|Proxy createOne(array $attributes = [])
 * @method static EntertainmentType|Proxy find(object|array|mixed $criteria)
 * @method static EntertainmentType|Proxy findOrCreate(array $attributes)
 * @method static EntertainmentType|Proxy first(string $sortedField = 'id')
 * @method static EntertainmentType|Proxy last(string $sortedField = 'id')
 * @method static EntertainmentType|Proxy random(array $attributes = [])
 * @method static EntertainmentType|Proxy randomOrCreate(array $attributes = [])
 * @method static EntertainmentTypeRepository|RepositoryProxy repository()
 * @method static EntertainmentType[]|Proxy[] all()
 * @method static EntertainmentType[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static EntertainmentType[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static EntertainmentType[]|Proxy[] findBy(array $attributes)
 * @method static EntertainmentType[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static EntertainmentType[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class EntertainmentTypeFactory extends ModelFactory
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
            'name' => self::faker()->words(4, true),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(EntertainmentType $entertainmentType): void {})
        ;
    }

    protected static function getClass(): string
    {
        return EntertainmentType::class;
    }
}
