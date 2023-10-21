<?php

namespace App\Factory;

use App\Entity\Paymentable;
use App\Repository\PaymentableRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Paymentable>
 *
 * @method        Paymentable|Proxy create(array|callable $attributes = [])
 * @method static Paymentable|Proxy createOne(array $attributes = [])
 * @method static Paymentable|Proxy find(object|array|mixed $criteria)
 * @method static Paymentable|Proxy findOrCreate(array $attributes)
 * @method static Paymentable|Proxy first(string $sortedField = 'id')
 * @method static Paymentable|Proxy last(string $sortedField = 'id')
 * @method static Paymentable|Proxy random(array $attributes = [])
 * @method static Paymentable|Proxy randomOrCreate(array $attributes = [])
 * @method static PaymentableRepository|RepositoryProxy repository()
 * @method static Paymentable[]|Proxy[] all()
 * @method static Paymentable[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Paymentable[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Paymentable[]|Proxy[] findBy(array $attributes)
 * @method static Paymentable[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Paymentable[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class PaymentableFactory extends ModelFactory
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
        $names = [
            'canette coca',
            'canette oasis',
            'bouteille bière',
            'bière pression',
            'crèpe',
            'gaufre',
            'sandwich',
            'hotdog',
            'ticket entrée',
            'ticket soirée',
            'goodies badges',
            'goodies stickers',
            'ecocup',
            'goodies t-shirt',
            'pass 3 jours'
        ];

        return [
            //'event' => EventFactory::new(),
            'name' => self::faker()->randomElement($names),
            //'typePaymentable' => TypePaymentableFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Paymentable $paymentable): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Paymentable::class;
    }
}
