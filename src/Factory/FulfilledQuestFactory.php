<?php

namespace App\Factory;

use App\Entity\FulfilledQuest;
use App\Repository\FulfilledQuestRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<FulfilledQuest>
 *
 * @method        FulfilledQuest|Proxy create(array|callable $attributes = [])
 * @method static FulfilledQuest|Proxy createOne(array $attributes = [])
 * @method static FulfilledQuest|Proxy find(object|array|mixed $criteria)
 * @method static FulfilledQuest|Proxy findOrCreate(array $attributes)
 * @method static FulfilledQuest|Proxy first(string $sortedField = 'id')
 * @method static FulfilledQuest|Proxy last(string $sortedField = 'id')
 * @method static FulfilledQuest|Proxy random(array $attributes = [])
 * @method static FulfilledQuest|Proxy randomOrCreate(array $attributes = [])
 * @method static FulfilledQuestRepository|RepositoryProxy repository()
 * @method static FulfilledQuest[]|Proxy[] all()
 * @method static FulfilledQuest[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static FulfilledQuest[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static FulfilledQuest[]|Proxy[] findBy(array $attributes)
 * @method static FulfilledQuest[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FulfilledQuest[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class FulfilledQuestFactory extends ModelFactory
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
            //'quest' => QuestFactory::new(),
            //'user' => UserTMFactory::new(),
            //'userGuild' => self::faker()->randomNumber(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(FulfilledQuest $fulfilledQuest): void {})
        ;
    }

    protected static function getClass(): string
    {
        return FulfilledQuest::class;
    }
}
