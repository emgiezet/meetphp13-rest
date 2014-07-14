<?php
/**
 * Created by PhpStorm.
 * User: mgz
 * Date: 7/14/14
 * Time: 10:29 AM
 */

namespace MeetPHP\CoreBundle\DataFixtures\ORM\Defaults;

use MeetPHP\CoreBundle\DataFixtures\ORM\YamlFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadUser extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getModelFixtures();
        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($data as $reference => $item) {
            $user = $userManager->createUser();
            $this->fromArray($user, $item);
            $userManager->updateUser($user, false);

            $this->addReference($reference, $user);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }

    public function getModelFile()
    {
        return 'user';
    }
}
