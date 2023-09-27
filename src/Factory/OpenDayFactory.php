<?php

namespace App\Factory;

use App\Entity\OpenDay;
use App\Repository\OpenDayRepository;
use DateTime;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<OpenDay>
 *
 * @method        OpenDay|Proxy create(array|callable $attributes = [])
 * @method static OpenDay|Proxy createOne(array $attributes = [])
 * @method static OpenDay|Proxy find(object|array|mixed $criteria)
 * @method static OpenDay|Proxy findOrCreate(array $attributes)
 * @method static OpenDay|Proxy first(string $sortedField = 'id')
 * @method static OpenDay|Proxy last(string $sortedField = 'id')
 * @method static OpenDay|Proxy random(array $attributes = [])
 * @method static OpenDay|Proxy randomOrCreate(array $attributes = [])
 * @method static OpenDayRepository|RepositoryProxy repository()
 * @method static OpenDay[]|Proxy[] all()
 * @method static OpenDay[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static OpenDay[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static OpenDay[]|Proxy[] findBy(array $attributes)
 * @method static OpenDay[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static OpenDay[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class OpenDayFactory extends ModelFactory
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
            'dayEnd' => self::faker()->dateTime(),
            'dayStart' => self::faker()->dateTime(),
            //'event' => EventFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(OpenDay $openDay): void {})
        ;
    }

    protected static function getClass(): string
    {
        return OpenDay::class;
    }
}
