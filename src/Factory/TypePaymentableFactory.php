<?php

namespace App\Factory;

use App\Entity\TypePaymentable;
use App\Repository\TypePaymentableRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TypePaymentable>
 *
 * @method        TypePaymentable|Proxy create(array|callable $attributes = [])
 * @method static TypePaymentable|Proxy createOne(array $attributes = [])
 * @method static TypePaymentable|Proxy find(object|array|mixed $criteria)
 * @method static TypePaymentable|Proxy findOrCreate(array $attributes)
 * @method static TypePaymentable|Proxy first(string $sortedField = 'id')
 * @method static TypePaymentable|Proxy last(string $sortedField = 'id')
 * @method static TypePaymentable|Proxy random(array $attributes = [])
 * @method static TypePaymentable|Proxy randomOrCreate(array $attributes = [])
 * @method static TypePaymentableRepository|RepositoryProxy repository()
 * @method static TypePaymentable[]|Proxy[] all()
 * @method static TypePaymentable[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TypePaymentable[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static TypePaymentable[]|Proxy[] findBy(array $attributes)
 * @method static TypePaymentable[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TypePaymentable[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TypePaymentableFactory extends ModelFactory
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
            'name' => self::faker()->word(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TypePaymentable $typePaymentable): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TypePaymentable::class;
    }
}
