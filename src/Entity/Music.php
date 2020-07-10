<?php

namespace App\Entity;

use App\Repository\MusicRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MusicRepository::class)
 */
class Music extends Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $singer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $band;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $titles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $album;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $compositor;

    /**
     * @ORM\OneToOne(targetEntity=DigitalMusic::class, mappedBy="media_id", cascade={"persist", "remove"})
     */
    private $digitalMusic;

    /**
     * @ORM\OneToOne(targetEntity=StockableMusic::class, mappedBy="media_id", cascade={"persist", "remove"})
     */
    private $stockableMusic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSinger(): ?string
    {
        return $this->singer;
    }

    public function setSinger(string $singer): self
    {
        $this->singer = $singer;

        return $this;
    }

    public function getBand(): ?string
    {
        return $this->band;
    }

    public function setBand(string $band): self
    {
        $this->band = $band;

        return $this;
    }

    public function getTitles(): ?string
    {
        return $this->titles;
    }

    public function setTitles(string $titles): self
    {
        $this->titles = $titles;

        return $this;
    }

    public function getAlbum(): ?string
    {
        return $this->album;
    }

    public function setAlbum(string $album): self
    {
        $this->album = $album;

        return $this;
    }

    public function getCompositor(): ?string
    {
        return $this->compositor;
    }

    public function setCompositor(string $compositor): self
    {
        $this->compositor = $compositor;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(\DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

        return $this;
    }

    public function getDigitalMusic(): ?DigitalMusic
    {
        return $this->digitalMusic;
    }

    public function setDigitalMusic(DigitalMusic $digitalMusic): self
    {
        $this->digitalMusic = $digitalMusic;

        // set the owning side of the relation if necessary
        if ($digitalMusic->getMediaId() !== $this) {
            $digitalMusic->setMediaId($this);
        }

        return $this;
    }

    public function getStockableMusic(): ?StockableMusic
    {
        return $this->stockableMusic;
    }

    public function setStockableMusic(StockableMusic $stockableMusic): self
    {
        $this->stockableMusic = $stockableMusic;

        // set the owning side of the relation if necessary
        if ($stockableMusic->getMediaId() !== $this) {
            $stockableMusic->setMediaId($this);
        }

        return $this;
    }
}
