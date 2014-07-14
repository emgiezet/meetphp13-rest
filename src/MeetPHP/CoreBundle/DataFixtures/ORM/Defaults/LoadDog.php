<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 7/14/14
 * Time: 10:29 AM
 */

namespace MeetPHP\CoreBundle\DataFixtures\ORM\Defaults;

use MeetPHP\CoreBundle\DataFixtures\ORM\YamlFixtures;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MeetPHP\CoreBundle\Entity\Dog;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadDog extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface {

    public function load(ObjectManager $manager)
    {
        $data = $this->getModelFixtures();


        foreach ($data as $reference => $item) {
            $dog = new Dog();

            $owner = $this->getReference($item['owner']);

            $dog->setOwner($owner);
            unset($item['owner']);

            $this->fromArray($dog, $item);

            $manager->persist($dog);

        }
        $manager->flush();
    }
    public function getOrder()
    {
        return 20;
    }

    public function getModelFile()
    {
        return 'dog';
    }
} 