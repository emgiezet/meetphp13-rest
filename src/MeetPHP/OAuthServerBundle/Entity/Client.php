<?php
namespace MeetPHP\OAuthServerBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     */
    protected $name;
    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * name getter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * name setter
     *
     * @param string $name name of client
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
