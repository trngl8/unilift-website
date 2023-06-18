<?php

namespace App\DataFixtures;

use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $order = new Order();
        $order->setAction('pay');
        $order->setAmount(300);
        $order->setCurrency('UAH');
        $order->setDeliveryEmail('delivery@test.com');
        $order->setDeliveryPhone('+3801111111');
        $order->setDescription('Description');

        $manager->persist($order);;
        $manager->flush();
    }
}
