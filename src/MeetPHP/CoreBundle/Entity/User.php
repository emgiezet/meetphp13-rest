<?php

namespace MeetPHP\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use FOS\UserBundle\Model\GroupInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * MeetPHP\CoreBundle\Entity\User
 *
 * @ORM\Table(name="user", indexes={
 *     @ORM\Index(name="username_idx", columns={"username"}),
 *     @ORM\Index(name="email_idx", columns={"email"})
 * })
 * @ORM\Entity()
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"username"})
 * @ExclusionPolicy("all")
 * @Hateoas\Relation("self", href = "expr('/api/users/' ~ object.getId())")
 *
 */
class User extends BaseUser
{
    const ROLE_USER = 'ROLE_USER';
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     * @Expose
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     * @Expose
     */
    protected $lastname;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Expose
     */
    protected $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Expose
     */
    protected $created;

    /**
     * @var string
     */
    protected $retypePassword;

    /**
     * @var string
     */
    protected $oldPassword;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MeetPHP\OAuthServerBundle\Entity\AccessToken", mappedBy="user")
     * @Expose
     */
    protected $tokens;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->enabled      = true;
        $this->created = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return trim($this->getName()) != "" ? $this->getName() : 'User create';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param  string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param  string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set created
     *
     * @param  \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param  \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add groups
     *
     * @param  GroupInterface $groups
     * @return User
     */
    public function addGroup(GroupInterface $group)
    {
        parent::addGroup($group);

        return $this;
    }

    /**
     * Remove all old groups and add new one
     *
     * @param  GroupInterface $group
     * @return User
     */
    public function setGroups(GroupInterface $group)
    {
        foreach ($this->getGroups() as $oldGroup) {
            $this->removeGroup($oldGroup);
        }
        parent::addGroup($group);

        return $this;
    }

    /**
     * Remove groups
     *
     * @param GroupInterface $groups
     */
    public function removeGroup(GroupInterface $group)
    {
        parent::removeGroup($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return parent::getGroups();
    }

    /**
     * Get user full name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * Set singleRole
     * Clear all user roles and set new one
     *
     * @param  string $role
     * @return User
     */
    public function setSingleRole($role)
    {
        foreach ($this->roles as $oldRole) {
            $this->removeRole($oldRole);
        }
        $this->addRole($role);
        $this->singleRole = $role;

        return $this;
    }

    /**
     * Post load doctrine event
     *
     * @ORM\PostLoad()
     */
    public function updateSingleRole()
    {
        $this->singleRole = $this->getSingleRole();
    }

    /**
     * Get singleRole
     *
     * @return string
     */
    public function getSingleRole()
    {
        $roles = $this->roles;

        return isset($roles[0]) ? $roles[0] : null;
    }

    /**
     * @return string
     */
    public function getSingleRoleName()
    {
        $constRole = $this->getSingleRole();
        if ($constRole != "" && isset(static::$userRoles[$constRole])) {
            return static::$userRoles[$constRole];
        }

        return null;
    }

    /**
     * Set retypePassword
     *
     * @param  string $retypePassword
     * @return User
     */
    public function setRetypePassword($retypePassword)
    {
        $this->retypePassword = $retypePassword;

        return $this;
    }

    /**
     * Get retypePassword
     *
     * @return string
     */
    public function getRetypePassword()
    {
        return $this->retypePassword;
    }

    /**
     * Set oldPassword
     *
     * @param  string $oldPassword
     * @return User
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Get oldPassword
     *
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return \DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }


    /**
     * Add tokens
     *
     * @param  \MeetPHP\OAuthServerBundle\Entity\AccessToken $tokens
     * @return User
     */
    public function addToken(\MeetPHP\OAuthServerBundle\Entity\AccessToken $tokens)
    {
        $this->tokens[] = $tokens;

        return $this;
    }

    /**
     * Remove tokens
     *
     * @param \MeetPHP\OAuthServerBundle\Entity\AccessToken $tokens
     */
    public function removeToken(\MeetPHP\OAuthServerBundle\Entity\AccessToken $tokens)
    {
        $this->tokens->removeElement($tokens);
    }

    /**
     * Get tokens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * Password validator
     *
     * @return boolean
     */
    public function isPasswordLegal()
    {
        return ($this->firstname != $this->plainPassword) && ($this->plainPassword !== 'dupa.8');
    }

    /**
     * Gets the value of userRoles.
     *
     * @return array
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Sets the value of userRoles.
     *
     * @param array $userRoles the userRoles
     *
     * @return self
     */
    public function setUserRoles(array $userRoles)
    {
        $this->userRoles = $userRoles;

        return $this;
    }

    /**
     * Sets the value of id.
     *
     * @param integer $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the value of email.
     *
     * @param string $email the email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }


    /**
     * Sets the value of tokens.
     *
     * @param ArrayCollection $tokens the tokens
     *
     * @return self
     */
    public function setTokens(ArrayCollection $tokens)
    {
        $this->tokens = $tokens;

        return $this;
    }

    /**
     * Gets the value of data.
     *
     * @return UserData
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Overloaded Base method
     * @see \FOS\UserBundle\Model\User::addRole()
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }
}
