<?php

namespace App\DataFixtures;

use App\Controller\Admin\GuildCrudController;
use App\Factory\EntertainmentFactory;
use App\Factory\EntertainmentReservationFactory;
use App\Factory\EntertainmentScheduleFactory;
use App\Factory\EntertainmentTypeFactory;
use App\Factory\EventFactory;
use App\Factory\StartedQuestFactory;
use App\Factory\GuildFactory;
use App\Factory\OpenDayFactory;
use App\Factory\OrganizationFactory;
use App\Factory\PaymentableFactory;
use App\Factory\PriceFactory;
use App\Factory\QuestFactory;
use App\Factory\ReportingFactory;
use App\Factory\RpgActivityFactory;
use App\Factory\RpgFactory;
use App\Factory\RpgReservationFactory;
use App\Factory\RpgTableFactory;
use App\Factory\RpgZoneFactory;
use App\Factory\TagFactory;
use App\Factory\TransitFactory;
use App\Factory\TriggerWarningFactory;
use App\Factory\TypePaymentableFactory;
use App\Factory\UrlFactory;
use App\Factory\UserParamsFactory;
use App\Factory\UserTMFactory;
use App\Factory\VolunteerShiftFactory;
use App\Factory\ZoneFactory;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Length;
use function Zenstruck\Foundry\faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserTMFactory::createOne([
            'name' => 'tmadmin',
            'email' => 'admin@dev.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        OrganizationFactory::createMany(3, function () {
            return [
                'urls' => UrlFactory::createMany(3)
            ];
        });

        $events = EventFactory::createMany(7, function () {
            return [
                'organization' => OrganizationFactory::random(),
                'urls' => UrlFactory::createMany(2),
                'address' => faker()->address(),
            ];
        });

        $typesPaymentable = ['billet entrée', 'consommable buvette', 'goodies', 'animation'];

        foreach ($typesPaymentable as $type) {
            TypePaymentableFactory::createOne([
                'name' => $type
            ]);
        }

        $paymentables = PaymentableFactory::createMany(20, function () {
            $array = ['billet entrée' => [
                'ticket entrée',
                'ticket soirée',
                'ticket earlybird',
                'pass 3 jours'
            ], 
            'consommable buvette' => [
                'canette coca',
                'canette oasis',
                'bouteille bière',
                'bière pression',
                'crèpe',
                'gaufre',
                'sandwich',
                'hotdog',
            ], 
            'goodies' => [
                'badges',
                'stickers',
                'ecocup',
                't-shirt',

            ], 
            'animation' => [
                'ticket individuel',
                'ticket famille',
                'ticket earlybird'
            ]];
            $types = ['billet entrée', 'consommable buvette', 'goodies', 'animation'];
            $type = $types[rand(0, count($types) - 1)];

            return [
                'name' => $array[$type][rand(0, count($array[$type]) -1)],
                'event' => EventFactory::random(),
                'typePaymentable' => TypePaymentableFactory::random(['name' => $type]),
            ];
        });

        PriceFactory::createMany(20, function () {
            return [
                'paymentable' => PaymentableFactory::random()
            ];
        });

        foreach ($paymentables as $paymentable) {
            if (($paymentable->getTypePaymentable()->getName() === 'consommable buvette') && $paymentable->getPrices()->isEmpty()) {
                PriceFactory::createOne([
                    'paymentable' => $paymentable,
                    'priceCondition' => null
                ]);
            }
            
        }

        PriceFactory::createMany(20, function () {
            return [
                'paymentable' => PaymentableFactory::random()
            ];
        });

        foreach ($events as $event) {
            $year = rand(2023, 2026);
            $month = rand(01, 12);
            $starts = [$year . '-' . $month . '-01 8:00', $year . '-' . $month . '-02 8:00', $year . '-' . $month . '-03 8:00'];
            $ends = [$year . '-' . $month . '-01 20:00', $year . '-' . $month . '-02 20:00', $year . '-' . $month . '-03 20:00'];
            foreach ($starts as $i => $v) {
                OpenDayFactory::createOne([
                    'dayStart' => new DateTime($v),
                    'dayEnd' => new DateTime($ends[$i]),
                    'event' => $event
                ]);
            };

            ZoneFactory::createMany(5, function () use ($event) {
                return [
                    'event' => $event
                ];
            });

            GuildFactory::createMany(rand(3, 6), function () use ($event) {
                return [
                    'event' => $event
                ];
            });

            QuestFactory::createMany(rand(5, 15), function () use ($event) {
                $isSecret = rand(0, 1);
                return [
                    'event' => $event,
                    'zone' => ZoneFactory::random(['event' => $event]),
                    'isSecret' => $isSecret == 1 ? true : false,
                    'guild' => $isSecret == 1 ? GuildFactory::random(['event' => $event]) : null
                ];
            });

            TransitFactory::createMany(rand(2, 5), function () use ($event) {
                return [
                    'event' => $event
                ];
            });
        }

        UserTMFactory::createMany(40, function () {
            $setGuild = rand(0, 1);
            return [
                'roles' => ['ROLE_USER', 'ROLE_VISITOR'],
                'guild' => $setGuild == 1 ? GuildFactory::random() : null
            ];
        });

        $volunteers = UserTMFactory::createMany(10, function () {
            return [
                'roles' => ['ROLE_USER', 'ROLE_VOLUNTEER'],
                'guild' => null
            ];
        });

        ReportingFactory::createMany(15, function () {
            $event = EventFactory::random();
            return [
                'event' => $event,
                'zone' => ZoneFactory::random(['event' => $event]),
                'user' => UserTMFactory::random()
            ];
        });

        VolunteerShiftFactory::createMany(25, function () use ($volunteers) {
            $event = EventFactory::random();
            $open = $event->getOpenDays()[rand(0, 2)];

            $shiftStart = rand($open->getDayStart()->getTimestamp(), $open->getDayEnd()->getTimestamp());
            $shiftEnd = rand($shiftStart, $open->getDayEnd()->getTimestamp());

            $dateTimeStart = new DateTime();
            $dateTimeStart->setTimestamp($shiftStart);
            $dateTimeEnd = new DateTime();
            $dateTimeEnd->setTimestamp($shiftEnd);

            return [
                'event' => $event,
                'shiftStart' => $dateTimeStart,
                'shiftEnd' => $dateTimeEnd,
                'zone' => ZoneFactory::random(['event' => $event]),
                'user' => $volunteers[rand(0, (count($volunteers) - 1))]
            ];
        });

        StartedQuestFactory::createMany(50, function () {
            $event = EventFactory::random();
            return [
                'quest' => QuestFactory::random(['event' => $event]),
                'user' => UserTMFactory::random(),
                'userGuild' => GuildFactory::random(['event' => $event])
            ];
        });

        TagFactory::createMany(20);

        TriggerWarningFactory::createMany(20);

        RpgFactory::createMany(20, function () {
            return [
                'tags' => TagFactory::randomRange(0, 5),
                'triggerWarnings' => TriggerWarningFactory::randomRange(0, 5)
            ];
        });

        foreach ($events as $event) {
            if (count($event->getZones()) > 0) {
                $rpgZone = RpgZoneFactory::createOne([
                    'event' => $event,
                    'zone' => ZoneFactory::random(['event' => $event])
                ]);

                RpgActivityFactory::createMany(5, function () use ($rpgZone) {
                    return [
                        'rpgZone' => $rpgZone
                    ];
                });
            }
        }

        RpgTableFactory::createMany(8, function () {
            return [
                'rpgActivity' => RpgActivityFactory::random(),
                'userGm' => UserTMFactory::random()
            ];
        });

        RpgReservationFactory::createMany(15, function () {
            $registeredUser = rand(0, 1);

            if ($registeredUser == 1) {
                $user =  UserTMFactory::random();
                return [
                    'user' => $user,
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                    'phoneNumber' => $user->getTelephone(),
                    'rpgTable' => RpgTableFactory::random()
                ];
            } else {
                return [
                    'rpgTable' => RpgTableFactory::random()
                ];
            }
        });

        EntertainmentTypeFactory::createMany(5);

        EntertainmentFactory::createMany(20, function () {
            return [
                'entertainmentType' => EntertainmentTypeFactory::random(),
                'zone' => ZoneFactory::random()
            ];
        });

        EntertainmentScheduleFactory::createMany(30, function () {
            return [
                'entertainment' => EntertainmentFactory::random()
            ];
        });

        EntertainmentReservationFactory::createMany(80, function () {
            $registeredUser = rand(0, 1);

            if ($registeredUser == 1) {
                $user =  UserTMFactory::random();
                return [
                    'user' => $user,
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                    'phoneNumber' => $user->getTelephone(),
                    'entertainmentSchedule' => EntertainmentScheduleFactory::random()
                ];
            } else {
                return [
                    'entertainmentSchedule' => EntertainmentScheduleFactory::random()
                ];
            }
        });

        $manager->flush();
    }
}
