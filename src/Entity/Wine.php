<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WineRepository")
 */
class Wine
{
    const COLOR_RED = 'rouge';
    const COLOR_PINKY = 'rosé';
    const COLOR_WHITE = 'blanc';

    const AREAS = [
        'Alsace' => 'Alsace',
        'Bordeaux' => 'Bordeaux',
        'Beaujolais' => 'Beaujolais',
        'Bourgogne' => 'Bourgogne',
        'Bugey' => 'Bugey',
        'Champagne' => 'Champagne',
        'Corse' => 'Corse',
        'Jura' => 'Jura',
        'Languedoc' => 'Languedoc',
        'Lorraine' => 'Lorraine',
        'Loire' => 'Loire',
        'Provence' => 'Provence',
        'Roussillon' => 'Roussillon',
        'Rhône' => 'Rhône',
        'Savoie' => 'Savoie',
        'Sud-Ouest' => 'Sud-Ouest',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $origin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $dluo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbBottles;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $createdDate;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getNbBottles(): ?int
    {
        return $this->nbBottles;
    }

    public function setNbBottles(?int $nbBottles): self
    {
        $this->nbBottles = $nbBottles;

        return $this;
    }

    public function getDluo(): ?int
    {
        return $this->dluo;
    }

    public function setDluo(?int $dluo): self
    {
        $this->dluo = $dluo;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

}
