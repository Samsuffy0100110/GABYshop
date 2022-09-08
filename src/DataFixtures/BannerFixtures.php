<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Banner;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BannerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $banner = new Banner();
        $banner->setName("Bannière 1");
        $banner->setImage("https://picsum.photos/200/300?random=1");
        $banner->setPosition(1);
        $banner->setIsActive(true);
        $banner->setCreatedAt(new DateTime());
        $banner->setStartedAt(new DateTime());
        $banner->setEndedAt(new DateTime());
        $manager->persist($banner);

        $banner = new Banner();
        $banner->setName("Bannière 2");
        $banner->setImage("https://picsum.photos/200/300?random=2");
        $banner->setPosition(2);
        $banner->setIsActive(true);
        $banner->setCreatedAt(new DateTime());
        $banner->setStartedAt(new DateTime());
        $banner->setEndedAt(new DateTime());
        $manager->persist($banner);

        $banner = new Banner();
        $banner->setName("Bannière 3");
        $banner->setImage("https://picsum.photos/200/300?random=3");
        $banner->setPosition(3);
        $banner->setIsActive(false);
        $banner->setCreatedAt(new DateTime());
        $banner->setStartedAt(new DateTime());
        $banner->setEndedAt(new DateTime());
        $manager->persist($banner);

        $manager->flush();
    }
}
