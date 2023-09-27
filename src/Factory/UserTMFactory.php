<?php

namespace App\Factory;

use App\Entity\UserParams;
use App\Entity\UserTM;
use App\Repository\UserTMRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<UserTM>
 *
 * @method        UserTM|Proxy create(array|callable $attributes = [])
 * @method static UserTM|Proxy createOne(array $attributes = [])
 * @method static UserTM|Proxy find(object|array|mixed $criteria)
 * @method static UserTM|Proxy findOrCreate(array $attributes)
 * @method static UserTM|Proxy first(string $sortedField = 'id')
 * @method static UserTM|Proxy last(string $sortedField = 'id')
 * @method static UserTM|Proxy random(array $attributes = [])
 * @method static UserTM|Proxy randomOrCreate(array $attributes = [])
 * @method static UserTMRepository|RepositoryProxy repository()
 * @method static UserTM[]|Proxy[] all()
 * @method static UserTM[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static UserTM[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static UserTM[]|Proxy[] findBy(array $attributes)
 * @method static UserTM[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserTM[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UserTMFactory extends ModelFactory
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
        $phone = '06' . rand(10000000, 99999999);
        return [
            'email' => self::faker()->safeEmail(),
            'name' => self::faker()->userName(),
            'password' => 'app1234',
            'roles' => [],
            'telephone' => $phone,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            ->afterPersist(function (UserTM $userTM) {
                $params = new UserParams;
                $params
                    ->setAllNotifications(self::faker()->boolean())
                    ->setUser($userTM);
            });
    }

    protected static function getClass(): string
    {
        return UserTM::class;
    }
}
