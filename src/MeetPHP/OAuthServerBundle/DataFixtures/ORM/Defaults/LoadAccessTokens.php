<?php

namespace MeetPHP\OAuthServerBundle\DataFixtures\ORM\Defaults;

use Doctrine\Common\Persistence\ObjectManager;
use MeetPHP\OAuthServerBundle\Entity\AccessToken;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MeetPHP\CoreBundle\DataFixtures\ORM\YamlFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadAccessTokens extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $data = $this->getModelFixtures();
        foreach ($data as $reference => $item) {
            $object = new AccessToken();
            $this->fromArray($object, $item);

            $client = $this->getReference($item['client_id']);
            $user = $this->getReference($item['user_id']);

            $object->setClient($client);
            $object->setUser($user);
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
        return 50;
    }

    /**
     * {@inheritdoc}
     */
    public function getModelFile()
    {
        return 'access_token';
    }

    /**
     * @{inheritdoc}
     */
    protected function getBundle()
    {
        return 'MeetPHPOAuthServerBundle';
    }
}
