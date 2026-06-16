<?php

namespace App\Entity;

use App\Config\GenderType;
use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidateRepository::class)]
class Candidate {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(enumType: GenderType::class)]
    private ?GenderType $Gender = GenderType::Undecided;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Bio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $Birthdate = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $Height = null;

    #[ORM\Column(length: 100)]
    private ?string $Weight = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $HomeTown = null;

    #[ORM\Column]
    private bool $Married = true;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Income = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $PoliticalAffiliation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Interests = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Lifestyle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $AdditionalInformation = null;

    /**
     * @var Collection<int, UserVote>
     */
    #[ORM\OneToMany(targetEntity: UserVote::class, mappedBy: 'Candidate', orphanRemoval: true)]
    private Collection $UserVotes;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'Candidates')]
    #[ORM\JoinTable(name: 'candidate_category')] // Optional: customize the join table name
    #[ORM\JoinColumn(name: 'candidate_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'category_id', referencedColumnName: 'id')]
    private Collection $Categories;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ImgUrl = null;

    public function __construct()
    {
        $this->UserVotes = new ArrayCollection();
        $this->Categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->Bio;
    }

    public function setBio(?string $Bio): static
    {
        $this->Bio = $Bio;

        return $this;
    }

    public function getBirthdate(): ?\DateTime
    {
        return $this->Birthdate;
    }

    public function setBirthdate(?\DateTime $Birthdate): static
    {
        $this->Birthdate = $Birthdate;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->Height;
    }

    public function setHeight(?string $Height): static
    {
        $this->Height = $Height;

        return $this;
    }

    public function getWeight(): ?string
    {
        return $this->Weight;
    }

    public function setWeight(string $Weight): static
    {
        $this->Weight = $Weight;

        return $this;
    }

    public function getHomeTown(): ?string
    {
        return $this->HomeTown;
    }

    public function setHomeTown(?string $HomeTown): static
    {
        $this->HomeTown = $HomeTown;

        return $this;
    }

    public function isMarried(): ?bool
    {
        return $this->Married;
    }

    public function setMarried(bool $Married): static
    {
        $this->Married = $Married;

        return $this;
    }

    public function getIncome(): ?string
    {
        return $this->Income;
    }

    public function setIncome(?string $Income): static
    {
        $this->Income = $Income;

        return $this;
    }

    public function getPoliticalAffiliation(): ?string
    {
        return $this->PoliticalAffiliation;
    }

    public function setPoliticalAffiliation(?string $PoliticalAffiliation): static
    {
        $this->PoliticalAffiliation = $PoliticalAffiliation;

        return $this;
    }

    public function getInterests(): ?string
    {
        return $this->Interests;
    }

    public function setInterests(?string $Interests): static
    {
        $this->Interests = $Interests;

        return $this;
    }

    public function getLifestyle(): ?string
    {
        return $this->Lifestyle;
    }

    public function setLifestyle(?string $Lifestyle): static
    {
        $this->Lifestyle = $Lifestyle;

        return $this;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->AdditionalInformation;
    }

    public function setAdditionalInformation(?string $AdditionalInformation): static
    {
        $this->AdditionalInformation = $AdditionalInformation;

        return $this;
    }

    /**
     * @return Collection<int, UserVote>
     */
    public function getUserVotes(): Collection
    {
        return $this->UserVotes;
    }

    public function addUserVote(UserVote $userVote): static
    {
        if (!$this->UserVotes->contains($userVote))
        {
            $this->UserVotes->add($userVote);
            $userVote->setCandidate($this);
        }

        return $this;
    }

    public function removeUserVote(UserVote $userVote): static
    {
        if ($this->UserVotes->removeElement($userVote))
        {
            // set the owning side to null (unless already changed)
            if ($userVote->getCandidate() === $this)
            {
                $userVote->setCandidate(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->Categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->Categories->contains($category))
        {
            $this->Categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->Categories->removeElement($category);

        return $this;
    }

    public function getGender(): ?GenderType
    {
        return $this->Gender;
    }

    public function setGender(GenderType $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getImgUrl(): ?string
    {
        return $this->ImgUrl;
    }

    public function setImgUrl(?string $ImgUrl): static
    {
        $this->ImgUrl = $ImgUrl;

        return $this;
    }
}
