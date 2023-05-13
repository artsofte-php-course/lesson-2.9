<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dueDate;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isCompleted = false;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $estimate;


    /**
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="tasks")
     */
    private $project;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeInterface $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function setProject(Project $project) 
    {
        $this->project = $project;
        return $this;
    }

    public function getProject()
    {
        return $this->project;
    }



    public function getEstimate()
    {
        return $this->estimate;
    }

    
    public function setEstimate($estimate) 
    {
        $this->estimate = $estimate;
    }


    public function isCompleted()
    {
        return $this->isCompleted;
    }

    public function setCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
    }

}
