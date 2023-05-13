<?php 
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Project {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    protected $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *  min=10,
     *  max=255,
     *  minMessage="Length must be a longer than {{ limit }}",
     *  maxMessage="Length must be a less than {{ limit }}"
     * 
     * )
     * @ORM\Column(type="string", nullable=false, length=255)
     */
    protected $name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *  min=3,
     *  max=10,
     *  minMessage="Length must be a longer than {{ limit }}",
     *  maxMessage="Length must be a less than {{ limit }}"
     * )
     * @Assert\Regex(
     *      pattern = "/^[a-zA-Z]+$/",
     *      message = "Must be a A-z string"
     * )
     * 
     * @ORM\Column(type="string", nullable=false, length=10)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="project")
     */
    protected $tasks;

    
    /**
     * @ORM\Column(type="string", nullable=false, length=255)
     */
    protected $user;

    public function __construct($name, $key)
    {
        $this->name = $name;
        $this->slug = $key;
        $this->tasks = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }


    public function setName($name) 
    {
        $this->name = $name;
    }


    public function setSlug($slug) 
    {
        $this->slug = $slug;
    }


    public function addTask(Task $task): self 
    {
       if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
       }

       return $this;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($username) 
    {
        $this->user = $username;
    }

}