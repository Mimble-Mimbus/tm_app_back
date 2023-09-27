<?php

namespace App\Factory;

use App\Entity\TriggerWarning;
use App\Repository\TriggerWarningRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TriggerWarning>
 *
 * @method        TriggerWarning|Proxy create(array|callable $attributes = [])
 * @method static TriggerWarning|Proxy createOne(array $attributes = [])
 * @method static TriggerWarning|Proxy find(object|array|mixed $criteria)
 * @method static TriggerWarning|Proxy findOrCreate(array $attributes)
 * @method static TriggerWarning|Proxy first(string $sortedField = 'id')
 * @method static TriggerWarning|Proxy last(string $sortedField = 'id')
 * @method static TriggerWarning|Proxy random(array $attributes = [])
 * @method static TriggerWarning|Proxy randomOrCreate(array $attributes = [])
 * @method static TriggerWarningRepository|RepositoryProxy repository()
 * @method static TriggerWarning[]|Proxy[] all()
 * @method static TriggerWarning[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TriggerWarning[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static TriggerWarning[]|Proxy[] findBy(array $attributes)
 * @method static TriggerWarning[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TriggerWarning[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TriggerWarningFactory extends ModelFactory
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
            'theme' => self::faker()->unique()->words(2, true),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(TriggerWarning $triggerWarning): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TriggerWarning::class;
    }
}
