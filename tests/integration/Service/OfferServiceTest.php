<?php

namespace integration\Service;

use App\Entity\Offer;
use App\Service\OfferService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OfferServiceTest extends KernelTestCase
{
    private $offerService;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->offerService = $container->get(OfferService::class);
    }

    public function testGetOffers() : void
    {
        $offers = $this->offerService->getOffers();

        $this->assertIsArray($offers);
        $this->assertNotEmpty($offers);
        $this->assertInstanceOf(Offer::class, $offers[0]);
    }
}
