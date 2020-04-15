<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Un utilisateur est déjà inscrit avec cette adresse email, veuillez en choisir un autre."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Veuillez renseigner votre prénom."
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Veuillez renseigner votre nom."
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message="Veuillez renseigner un email valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(
     *     message="Veuillez renseigner une url valide pour votre photos."
     * )
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(
     *     propertyPath="hash", message="Le mot de passe renseigné n'est pas identique."
     * )
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
     */
    private $comments;


    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->purchases = new ArrayCollection();
    }

    public function getFullName(){
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * Permet d'initialiser le slug
     * //On laisse l'entité gérer le slug
     * //On prévient Doctrine, avant de créer ou mettre à jour un comic, il vérifie si slug ou pas. Si y a pas, il crée des slug avec le plugin Slugify
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function createSlug()
    {
        if (empty($this->slug)){
            $slugify = New Slugify();
            $this -> slug = $slugify -> slugify($this->firstname . ' ' . $this->lastname);
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        // Elle retourne un tableau de chaîne de caractère qui contient les roles que l'user doit avoir
        // La fonction map transforme les roles du arraycollection en chaine de caractère et toArray retransforme le tout en tableau conventionnel PHP, en simple array contenant des chaines de caractères, ce dont la fonction getRoles a besoin
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();


        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
        // Elle retourne le mdp

        return $this->hash;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        // Permet de renvoyer le sel qui a été utilisé pour encoder le password
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        // Retourne ce avec quoi l'user va se connecter donc pour nous ce sera l'email

        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

}
