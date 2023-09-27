<?php

namespace App\Factory;

use App\Entity\RpgReservation;
use App\Repository\RpgReservationRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<RpgReservation>
 *
 * @method        RpgReservation|Proxy create(array|callable $attributes = [])
 * @method static RpgReservation|Proxy createOne(array $attributes = [])
 * @method static RpgReservation|Proxy find(object|array|mixed $criteria)
 * @method static RpgReservation|Proxy findOrCreate(array $attributes)
 * @method static RpgReservation|Proxy first(string $sortedField = 'id')
 * @method static RpgReservation|Proxy last(string $sortedField = 'id')
 * @method static RpgReservation|Proxy random(array $attributes = [])
 * @method static RpgReservation|Proxy randomOrCreate(array $attributes = [])
 * @method static RpgReservationRepository|RepositoryProxy repository()
 * @method static RpgReservation[]|Proxy[] all()
 * @method static RpgReservation[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static RpgReservation[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static RpgReservation[]|Proxy[] findBy(array $attributes)
 * @method static RpgReservation[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RpgReservation[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class RpgReservationFactory extends ModelFactory
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
            'bookings' => self::faker()->randomNumber(1, 3),
            'email' => self::faker()->safeEmail(),
            'name' => self::faker()->name(),
            'phoneNumber' => self::faker()->phoneNumber(),
            //'rpgTable' => RpgTableFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(RpgReservation $rpgReservation): void {})
        ;
    }

    protected static function getClass(): string
    {
        return RpgReservation::class;
    }
}
