<?php

namespace App\Factory;

use App\Entity\EntertainmentReservation;
use App\Repository\EntertainmentReservationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<EntertainmentReservation>
 *
 * @method        EntertainmentReservation|Proxy create(array|callable $attributes = [])
 * @method static EntertainmentReservation|Proxy createOne(array $attributes = [])
 * @method static EntertainmentReservation|Proxy find(object|array|mixed $criteria)
 * @method static EntertainmentReservation|Proxy findOrCreate(array $attributes)
 * @method static EntertainmentReservation|Proxy first(string $sortedField = 'id')
 * @method static EntertainmentReservation|Proxy last(string $sortedField = 'id')
 * @method static EntertainmentReservation|Proxy random(array $attributes = [])
 * @method static EntertainmentReservation|Proxy randomOrCreate(array $attributes = [])
 * @method static EntertainmentReservationRepository|RepositoryProxy repository()
 * @method static EntertainmentReservation[]|Proxy[] all()
 * @method static EntertainmentReservation[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static EntertainmentReservation[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static EntertainmentReservation[]|Proxy[] findBy(array $attributes)
 * @method static EntertainmentReservation[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static EntertainmentReservation[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class EntertainmentReservationFactory extends ModelFactory
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
            'bookings' => self::faker()->numberBetween(1, 6),
            'email' => self::faker()->text(255),
            //'entertainmentSchedule' => EntertainmentScheduleFactory::new(),
            'name' => self::faker()->text(255),
            'phoneNumber' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(EntertainmentReservation $entertainmentReservation): void {})
        ;
    }

    protected static function getClass(): string
    {
        return EntertainmentReservation::class;
    }
}
