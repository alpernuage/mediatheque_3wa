<?php
namespace App\Entity;
use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 * @UniqueEntity( fields={"username"},
 *                message="Identifiant déjà utilisé"
 *                )
 * @UniqueEntity( fields={"email"},
 *                message="E-mail déjà utilisé"
 *                )
 */
class Member implements UserInterface{

	/**
	 *
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $first_name;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $last_name;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlanK
	 */
	private $username;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $password;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\Email( message = "Format d'e-mail invalide : '{{ value }}'"
	 *                )
	 */
	private $email;

	/**
	 *
	 * @ORM\Column(type="string", length=500)
	 * @Assert\NotBlank
	 *
	 */
	private $address1;

	/**
	 *
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $address2;

	/**
	 *
	 * @ORM\Column(type="string", length=50)
	 * @Assert\Length( min = 5,
	 *                 max = 5,
	 *                 exactMessage = "Le code postal doit comporter exactement {{ limit }} caractères",
	 *                 allowEmptyString = false
	 *                 )
	 * @Assert\Regex( pattern="/\d/",
	 *                match=true,
	 *                message="Le code postal ne doit comporter que des chiffres"
	 *                )
	 */
	private $zipcode;

	/**
	 *
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	private $city;

	/**
	 *
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	private $phone;

	/**
	 *
	 * @ORM\ManyToOne(targetEntity=Staff::class)
	 * @Assert\NotBlanK
	 */
	private $created_by_staff;

	/**
	 *
	 * @ORM\OneToMany(targetEntity=Stream::class, mappedBy="member")
	 */
	private $streams;

	/**
	 *
	 * @ORM\OneToMany(targetEntity=Borrow::class, mappedBy="member")
	 */
	private $borrows;

	public function __construct(){
		$this->streams = new ArrayCollection();
		$this->borrows = new ArrayCollection();
	}

	public function getId(): ?int{
		return $this->id;
	}

	public function getFirstName(): ?string{
		return $this->first_name;
	}

	public function setFirstName(string $first_name): self{
		$this->first_name = $first_name;

		return $this;
	}

	public function getLastName(): ?string{
		return $this->last_name;
	}

	public function setLastName(string $last_name): self{
		$this->last_name = $last_name;

		return $this;
	}

	public function getUsername(): ?string{
		return $this->username;
	}

	public function setUsername(string $username): self{
		$this->username = $username;

		return $this;
	}

	public function getPassword(): ?string{
		return $this->password;
	}

	public function setPassword(string $password): self{
		$this->password = $password;

		return $this;
	}

	public function getEmail(): ?string{
		return $this->email;
	}

	public function setEmail(string $email): self{
		$this->email = $email;

		return $this;
	}

	public function getAddress1(): ?string{
		return $this->address1;
	}

	public function setAddress1(string $address1): self{
		$this->address1 = $address1;

		return $this;
	}

	public function getAddress2(): ?string{
		return $this->address2;
	}

	public function setAddress2(?string $address2): self{
		$this->address2 = $address2;

		return $this;
	}

	public function getZipcode(): ?string{
		return $this->zipcode;
	}

	public function setZipcode(string $zipcode): self{
		$this->zipcode = $zipcode;

		return $this;
	}

	public function getCity(): ?string{
		return $this->city;
	}

	public function setCity(string $city): self{
		$this->city = $city;

		return $this;
	}

	public function getPhone(): ?string{
		return $this->phone;
	}

	public function setPhone(?string $phone): self{
		$this->phone = $phone;

		return $this;
	}

	public function getCreatedByStaff(): ?Staff{
		return $this->created_by_staff;
	}

	public function setCreatedByStaff(?Staff $created_by_staff): self{
		$this->created_by_staff = $created_by_staff;

		return $this;
	}

	/**
	 *
	 * @return Collection|Stream[]
	 */
	public function getStreams(): Collection{
		return $this->streams;
	}

	public function addStream(Stream $stream): self{
		if (!$this->streams->contains($stream)){
			$this->streams[] = $stream;
			$stream->setMember($this);
		}

		return $this;
	}

	public function removeStream(Stream $stream): self{
		if ($this->streams->contains($stream)){
			$this->streams->removeElement($stream);
			// set the owning side to null (unless already changed)
			if ($stream->getMember() === $this){
				$stream->setMember(null);
			}
		}

		return $this;
	}

	/**
	 *
	 * @return Collection|Borrow[]
	 */
	public function getBorrows(): Collection{
		return $this->borrows;
	}

	public function addBorrow(Borrow $borrow): self{
		if (!$this->borrows->contains($borrow)){
			$this->borrows[] = $borrow;
			$borrow->setMember($this);
		}

		return $this;
	}

	public function removeBorrow(Borrow $borrow): self{
		if ($this->borrows->contains($borrow)){
			$this->borrows->removeElement($borrow);
			// set the owning side to null (unless already changed)
			if ($borrow->getMember() === $this){
				$borrow->setMember(null);
			}
		}

		return $this;
	}

	public function __toString(): string{
		return $this->username;
	}

	/**
	 * @todo ?
	 * {@inheritDoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
	 */
	public function eraseCredentials(){}

	/**
	 * Empty
	 *
	 * {@inheritdoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getSalt()
	 */
	public function getSalt(){
		return '';
	}

	/**
	 * There is only one role "ROLE_USER" and there is <b>NO NOOED TO setRoles</b> as Member just gets only one constant role
	 *
	 * {@inheritdoc}
	 * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
	 */
	public function getRoles(): array{
		return [
			'ROLE_USER'
		];
	}
}
