<?php

namespace App\Entity;

use App\Repository\UserVoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserVoteRepository::class)]
class UserVote {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'UserVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\ManyToOne(inversedBy: 'UserVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Candidate $Candidate = null;

    #[ORM\Column]
    private ?\DateTime $CreatedOn = null;

    #[ORM\Column]
    private ?\DateTime $ModifiedOn = null;

    #[ORM\Column]
    private ?bool $Smash = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getCandidate(): ?Candidate
    {
        return $this->Candidate;
    }

    public function setCandidate(?Candidate $Candidate): static
    {
        $this->Candidate = $Candidate;

        return $this;
    }

    public function getCreatedOn(): ?\DateTime
    {
        return $this->CreatedOn;
    }

    public function setCreatedOn(\DateTime $CreatedOn): static
    {
        $this->CreatedOn = $CreatedOn;

        return $this;
    }

    public function getModifiedOn(): ?\DateTime
    {
        return $this->ModifiedOn;
    }

    public function setModifiedOn(\DateTime $ModifiedOn): static
    {
        $this->ModifiedOn = $ModifiedOn;

        return $this;
    }

    public function isSmash(): ?bool
    {
        return $this->Smash;
    }

    public function setSmash(bool $Smash): static
    {
        $this->Smash = $Smash;

        return $this;
    }
}
