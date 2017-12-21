<?php

namespace App\Entity;

use \Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="public.user", uniqueConstraints={@ORM\UniqueConstraint(name="user_email_key", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User {
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer", nullable=false)
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="SEQUENCE")
   * @ORM\SequenceGenerator(sequenceName="user_id_seq", allocationSize=1, initialValue=1)
   */
  private $id;

  /**
   * @var string|null
   *
   * @ORM\Column(name="`firstName`", type="string", length=255, nullable=true)
   */
  private $firstName;

  /**
   * @var string|null
   *
   * @ORM\Column(name="`lastName`", type="string", length=255, nullable=true)
   */
  private $lastName;

  /**
   * @var string|null
   *
   * @ORM\Column(name="email", type="string", length=255, nullable=true)
   */
  private $email;

  /**
   * @var string|null
   *
   * @ORM\Column(name="`password_digest`", type="string", length=255, nullable=true)
   */
  private $passwordDigest;


  /**
   * Get public.
   *
   * @return array
   */
  public function getPublic() {
    return [
      "id" => $this->id,
      "email" => $this->email,
      "firstName" => $this->firstName,
      "lastName" => $this->lastName
    ];
  }

  /**
   * Get id.
   *
   * @return int
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set firstname.
   *
   * @param string|null $firstName
   *
   * @return User
   */
  public function setFirstName($firstName = null) {
    $this->firstName = $firstName;

    return $this;
  }

  /**
   * Get firstname.
   *
   * @return string|null
   */
  public function getFirstName() {
    return $this->firstName;
  }

  /**
   * Set lastname.
   *
   * @param string|null $lastName
   *
   * @return User
   */
  public function setLastName($lastName = null) {
    $this->lastName = $lastName;

    return $this;
  }

  /**
   * Get lastname.
   *
   * @return string|null
   */
  public function getLastName() {
    return $this->lastName;
  }

  /**
   * Set email.
   *
   * @param string|null $email
   *
   * @return User
   */
  public function setEmail($email = null) {
    $this->email = $email;

    return $this;
  }

  /**
   * Get email.
   *
   * @return string|null
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * Set passwordDigest.
   *
   * @param string|null $password
   *
   * @return User
   */
  public function setPassword($password = null) {
    $this->passwordDigest = password_hash($password, PASSWORD_BCRYPT);

    return $this;
  }

  /**
   * Get passwordDigest.
   *
   * @return string|null
   */
  public function getPasswordDigest() {
    return $this->passwordDigest;
  }
}
