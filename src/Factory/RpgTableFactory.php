<?php

namespace App\Factory;

use App\Entity\RpgTable;
use App\Repository\RpgTableRepository;
use DateTime;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RpgTable>
 *
 * @method        RpgTable|Proxy create(array|callable $attributes = [])
 * @method static RpgTable|Proxy createOne(array $attributes = [])
 * @method static RpgTable|Proxy find(object|array|mixed $criteria)
 * @method static RpgTable|Proxy findOrCreate(array $attributes)
 * @method static RpgTable|Proxy first(string $sortedField = 'id')
 * @method static RpgTable|Proxy last(string $sortedField = 'id')
 * @method static RpgTable|Proxy random(array $attributes = [])
 * @method static RpgTable|Proxy randomOrCreate(array $attributes = [])
 * @method static RpgTableRepository|RepositoryProxy repository()
 * @method static RpgTable[]|Proxy[] all()
 * @method static RpgTable[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static RpgTable[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static RpgTable[]|Proxy[] findBy(array $attributes)
 * @method static RpgTable[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RpgTable[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RpgTableFactory extends ModelFactory
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
        $starts = [
            '2024-01-01 9:00',
            '2024-01-01 10:00',
            '2024-01-01 13:00',
            '2024-01-01 15:00',
            '2024-01-02 8:00',
            '2024-01-02 13:00',
            '2024-01-02 10:00',
            '2024-01-02 15:00',
            '2024-01-03 8:00',
            '2024-01-03 10:00',
            '2024-01-03 13:00',
            '2024-01-03 15:00',
        ];

        return [
            'duration' => self::faker()->numberBetween(3, 5),
            'isCanceled' => self::faker()->boolean(),
            //'rpgActivity' => RpgActivityFactory::new(),
            'start' => new DateTime(self::faker()->randomElement($starts)),
            //'userGm' => UserTMFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(RpgTable $rpgTable): void {})
        ;
    }

    protected static function getClass(): string
    {
        return RpgTable::class;
    }
}
