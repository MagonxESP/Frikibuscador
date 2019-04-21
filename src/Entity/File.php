<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int $id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=120)
     * @var string $name
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $url
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $summary
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=120)
     * @var string $title
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $path
     */
    private $path;

    /**
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param string $name
     * @return File
     */
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string {
        return $this->url;
    }

    /**
     * @param string $url
     * @return File
     */
    public function setUrl($url): self {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSummary(): ?string {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return File
     */
    public function setSummary(string $summary): self {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * @param string $title
     * @return File
     */
    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void {
        $this->path = $path;
    }

}
