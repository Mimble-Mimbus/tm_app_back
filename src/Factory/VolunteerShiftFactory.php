<?php

namespace App\Factory;

use App\Entity\VolunteerShift;
use App\Repository\VolunteerShiftRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<VolunteerShift>
 *
 * @method        VolunteerShift|Proxy create(array|callable $attributes = [])
 * @method static VolunteerShift|Proxy createOne(array $attributes = [])
 * @method static VolunteerShift|Proxy find(object|array|mixed $criteria)
 * @method static VolunteerShift|Proxy findOrCreate(array $attributes)
 * @method static VolunteerShift|Proxy first(string $sortedField = 'id')
 * @method static VolunteerShift|Proxy last(string $sortedField = 'id')
 * @method static VolunteerShift|Proxy random(array $attributes = [])
 * @method static VolunteerShift|Proxy randomOrCreate(array $attributes = [])
 * @method static VolunteerShiftRepository|RepositoryProxy repository()
 * @method static VolunteerShift[]|Proxy[] all()
 * @method static VolunteerShift[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static VolunteerShift[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static VolunteerShift[]|Proxy[] findBy(array $attributes)
 * @method static VolunteerShift[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static VolunteerShift[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class VolunteerShiftFactory extends ModelFactory
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
            'shiftEnd' => self::faker()->dateTime(),
            'shiftStart' => self::faker()->dateTime(),
            //'user' => UserTMFactory::new(),
            //'zone' => ZoneFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(VolunteerShift $volunteerShift): void {})
        ;
    }

    protected static function getClass(): string
    {
        return VolunteerShift::class;
    }
}
