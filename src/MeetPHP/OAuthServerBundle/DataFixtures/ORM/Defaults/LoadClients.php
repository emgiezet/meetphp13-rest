<?php

namespace MeetPHP\OAuthServerBundle\DataFixtures\ORM\Defaults;

use Doctrine\Common\Persistence\ObjectManager;
use MeetPHP\OAuthServerBundle\Entity\Client;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MeetPHP\CoreBundle\DataFixtures\ORM\YamlFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadClients extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $data = $this->getModelFixtures();
        foreach ($data as $reference => $item) {
            $object = new Client();
            $this->fromArray($object, $item);
            $manager->persist($object);
            $this->addReference($reference, $object);
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * {@inheritdoc}
     */
    public function getModelFile()
    {
        return 'clients';
    }

    /**
     * @{inheritdoc}
     */
    protected function getBundle()
    {
        return 'MeetPHPOAuthServerBundle';
    }
}
