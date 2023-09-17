<?php

namespace App\Factory;

use App\Entity\EntertainmentSchedule;
use App\Repository\EntertainmentScheduleRepository;
use DateTime;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<EntertainmentSchedule>
 *
 * @method        EntertainmentSchedule|Proxy create(array|callable $attributes = [])
 * @method static EntertainmentSchedule|Proxy createOne(array $attributes = [])
 * @method static EntertainmentSchedule|Proxy find(object|array|mixed $criteria)
 * @method static EntertainmentSchedule|Proxy findOrCreate(array $attributes)
 * @method static EntertainmentSchedule|Proxy first(string $sortedField = 'id')
 * @method static EntertainmentSchedule|Proxy last(string $sortedField = 'id')
 * @method static EntertainmentSchedule|Proxy random(array $attributes = [])
 * @method static EntertainmentSchedule|Proxy randomOrCreate(array $attributes = [])
 * @method static EntertainmentScheduleRepository|RepositoryProxy repository()
 * @method static EntertainmentSchedule[]|Proxy[] all()
 * @method static EntertainmentSchedule[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static EntertainmentSchedule[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static EntertainmentSchedule[]|Proxy[] findBy(array $attributes)
 * @method static EntertainmentSchedule[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static EntertainmentSchedule[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class EntertainmentScheduleFactory extends ModelFactory
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
            'duration' => self::faker()->randomNumber(1, 3),
            //'entertainment' => EntertainmentFactory::new(),
            'isCanceled' => self::faker()->boolean(),
            'start' => new DateTime(self::faker()->randomElement($starts)),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(EntertainmentSchedule $entertainmentSchedule): void {})
        ;
    }

    protected static function getClass(): string
    {
        return EntertainmentSchedule::class;
    }
}
