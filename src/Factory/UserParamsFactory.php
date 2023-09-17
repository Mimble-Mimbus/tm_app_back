<?php

namespace App\Factory;

use App\Entity\UserParams;
use App\Repository\UserParamsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<UserParams>
 *
 * @method        UserParams|Proxy create(array|callable $attributes = [])
 * @method static UserParams|Proxy createOne(array $attributes = [])
 * @method static UserParams|Proxy find(object|array|mixed $criteria)
 * @method static UserParams|Proxy findOrCreate(array $attributes)
 * @method static UserParams|Proxy first(string $sortedField = 'id')
 * @method static UserParams|Proxy last(string $sortedField = 'id')
 * @method static UserParams|Proxy random(array $attributes = [])
 * @method static UserParams|Proxy randomOrCreate(array $attributes = [])
 * @method static UserParamsRepository|RepositoryProxy repository()
 * @method static UserParams[]|Proxy[] all()
 * @method static UserParams[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static UserParams[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static UserParams[]|Proxy[] findBy(array $attributes)
 * @method static UserParams[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserParams[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UserParamsFactory extends ModelFactory
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
            'allNotifications' => self::faker()->boolean(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(UserParams $userParams): void {})
        ;
    }

    protected static function getClass(): string
    {
        return UserParams::class;
    }
}
