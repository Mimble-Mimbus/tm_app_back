<?php

namespace App\Factory;

use App\Entity\StartedQuest;
use App\Repository\StartedQuestRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<StartedQuest>
 *
 * @method        StartedQuest|Proxy create(array|callable $attributes = [])
 * @method static StartedQuest|Proxy createOne(array $attributes = [])
 * @method static StartedQuest|Proxy find(object|array|mixed $criteria)
 * @method static StartedQuest|Proxy findOrCreate(array $attributes)
 * @method static StartedQuest|Proxy first(string $sortedField = 'id')
 * @method static StartedQuest|Proxy last(string $sortedField = 'id')
 * @method static StartedQuest|Proxy random(array $attributes = [])
 * @method static StartedQuest|Proxy randomOrCreate(array $attributes = [])
 * @method static StartedQuestRepository|RepositoryProxy repository()
 * @method static StartedQuest[]|Proxy[] all()
 * @method static StartedQuest[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static StartedQuest[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static StartedQuest[]|Proxy[] findBy(array $attributes)
 * @method static StartedQuest[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static StartedQuest[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class StartedQuestFactory extends ModelFactory
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
        $fulfilled = self::faker()->boolean();
        $aborted = false;
        $comment = null;
        $difficulty = null;

        if (!$fulfilled) {
            $aborted = self::faker()->boolean();
        }

        if($aborted) {
            $comment = self::faker()->text();
            $difficulty = self::faker()->numberBetween(0, 5);
        }

        return [
            'date' => self::faker()->dateTime(),
            'isFulfilled' => $fulfilled,
            'isAborted' => $aborted,
            'difficulty' => $difficulty,
            'comment' => $comment
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
            // ->afterInstantiate(function(StartedQuest $startedQuest): void {})
        ;
    }

    protected static function getClass(): string
    {
        return StartedQuest::class;
    }
}
