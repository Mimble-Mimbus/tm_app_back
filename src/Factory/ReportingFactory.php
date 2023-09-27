<?php

namespace App\Factory;

use App\Entity\Reporting;
use App\Repository\ReportingRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Reporting>
 *
 * @method        Reporting|Proxy create(array|callable $attributes = [])
 * @method static Reporting|Proxy createOne(array $attributes = [])
 * @method static Reporting|Proxy find(object|array|mixed $criteria)
 * @method static Reporting|Proxy findOrCreate(array $attributes)
 * @method static Reporting|Proxy first(string $sortedField = 'id')
 * @method static Reporting|Proxy last(string $sortedField = 'id')
 * @method static Reporting|Proxy random(array $attributes = [])
 * @method static Reporting|Proxy randomOrCreate(array $attributes = [])
 * @method static ReportingRepository|RepositoryProxy repository()
 * @method static Reporting[]|Proxy[] all()
 * @method static Reporting[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Reporting[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Reporting[]|Proxy[] findBy(array $attributes)
 * @method static Reporting[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Reporting[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ReportingFactory extends ModelFactory
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
            'date' => self::faker()->dateTime(),
            'emergencyLevel' => self::faker()->randomElement(['bas', 'moyen', 'haut', 'extrême']),
            //'event' => EventFactory::new(),
            'text' => self::faker()->text(),
            'type' => self::faker()->randomElement(['dégradation', 'aggression', 'déchets', 'dysfonctionnement']),
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
            // ->afterInstantiate(function(Reporting $reporting): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Reporting::class;
    }
}
