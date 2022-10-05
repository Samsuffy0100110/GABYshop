<?php

namespace App\DataFixtures\Order;

use DateTime;
use App\Entity\Order\Order;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Order\ShippingFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDERS = [

        [
            'reference' => '123456789',
            'user' => 'User',
            'shipping' => 'Chronopost',
            'state' => 1,
        ],
        [
            'reference' => '987654321',
            'user' => 'User',
            'shipping' => 'Colissimo',
            'state' => 1,
        ],
        [
            'reference' => '1234567898',
            'user' => 'User',
            'shipping' => 'Mondial Relay',
            'state' => 1,
        ],
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::ORDERS as $orderData) {
            $order = new Order();
            $order->setReference($orderData['reference']);
            $order->setUser($this->getReference($orderData['user']));
            $order->setShipping($this->getReference($orderData['shipping']));
            $order->setState($orderData['state']);
            $order->setCreatedAt(new DateTime());
            $order->setUpdatedAt(new DateTime());
            $manager->persist($order);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ShippingFixtures::class,
        ];
    }
}