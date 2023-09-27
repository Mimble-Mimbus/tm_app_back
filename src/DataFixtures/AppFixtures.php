<?php

namespace App\DataFixtures;

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

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $organization = OrganizationFactory::createOne([
            'urls' => UrlFactory::createMany(3)
        ]);

        $event = EventFactory::createOne([
            'organization' => $organization,
            'urls' => UrlFactory::createMany(2)
        ]);


        TypePaymentableFactory::createMany(5);

        PaymentableFactory::createMany(20, function () use ($event) {
            return [
                'event' => $event,
                'typePaymentable' => TypePaymentableFactory::random()
            ];
        });

        PriceFactory::createMany(20, function () {
            return [
                'paymentable' => PaymentableFactory::random()
            ];
        });

        $starts = ['2024-01-01 8:00', '2024-01-02 8:00', '2024-01-03 8:00'];
        $ends = ['2024-01-01 20:00', '2024-01-02 20:00', '2024-01-03 20:00'];
        foreach ($starts as $i => $v) {
            OpenDayFactory::createOne([
                'dayStart' => new DateTime($v),
                'dayEnd' => new DateTime($ends[$i]),
                'event' => $event
            ]);
        };

        $zones = ZoneFactory::createMany(6, function () use ($event) {
            return [
                'event' => $event
            ];
        });

        GuildFactory::createMany(5, function () use ($event) {
            return [
                'event' => $event
            ];
        });

        UserTMFactory::createMany(20, function () {
            $setGuild = rand(0, 1);
            return [
                'guild' => $setGuild == 1 ? GuildFactory::random() : null
            ];
        });

        UserTMFactory::createOne([
            'name' => 'tmadmin',
            'email' => 'admin@dev.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        ReportingFactory::createMany(10, function () use ($event) {
            return [
                'event' => $event,
                'zone' => ZoneFactory::random(),
                'user' => UserTMFactory::random()
            ];
        });



        VolunteerShiftFactory::createMany(15, function () use ($event) {
            return [
                'event' => $event,
                'zone' => ZoneFactory::random(),
                'user' => UserTMFactory::random()
            ];
        });

        QuestFactory::createMany(30, function () use ($event) {
            return [
                'event' => $event,
                'zone' => ZoneFactory::random()
            ];
        });

        StartedQuestFactory::createMany(50, function () {
            return [
                'quest' => QuestFactory::random(),
                'user' => UserTMFactory::random(),
                'userGuild' => GuildFactory::random()
            ];
        });

        TagFactory::createMany(20);

        TriggerWarningFactory::createMany(20);

        RpgFactory::createMany(10, function () {
            return [
                'tags' => TagFactory::randomRange(0, 5),
                'triggerWarnings' => TriggerWarningFactory::randomRange(0, 5)
            ];
        });

        $rpgZone = RpgZoneFactory::createOne([
            'event' => $event,
            'zone' => ZoneFactory::random()
        ]);

        RpgActivityFactory::createMany(5, function () use ($rpgZone) {
            return [
                'rpgZone' => $rpgZone
            ];
        });

        RpgTableFactory::createMany(5, function () {
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

        EntertainmentFactory::createMany(10, function () {
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
