<?php

namespace Renovate\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="budget", type="integer")
	 */
	private $budget;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="time", type="integer")
	 */
	private $time;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_workers", type="integer")
	 */
	private $percentWorkers;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="percent_brigadier", type="integer")
	 */
	private $percentBrigadier;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="color", type="string", length=255)
	 */
	private $color;
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="project")
     * @var array
     */
    private $events;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     * @return Project
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Project
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set percentWorkers
     *
     * @param integer $percentWorkers
     * @return Project
     */
    public function setPercentWorkers($percentWorkers)
    {
        $this->percentWorkers = $percentWorkers;

        return $this;
    }

    /**
     * Get percentWorkers
     *
     * @return integer 
     */
    public function getPercentWorkers()
    {
        return $this->percentWorkers;
    }

    /**
     * Set percentBrigadier
     *
     * @param integer $percentBrigadier
     * @return Project
     */
    public function setPercentBrigadier($percentBrigadier)
    {
        $this->percentBrigadier = $percentBrigadier;

        return $this;
    }

    /**
     * Get percentBrigadier
     *
     * @return integer 
     */
    public function getPercentBrigadier()
    {
        return $this->percentBrigadier;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Project
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Project
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Add events
     *
     * @param \Renovate\MainBundle\Entity\Event $events
     * @return Project
     */
    public function addEvent(\Renovate\MainBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Renovate\MainBundle\Entity\Event $events
     */
    public function removeEvent(\Renovate\MainBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function getInArray()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'budget' => $this->getBudget(),
            'time' => $this->getTime(),
            'percentWorkers' => $this->getPercentWorkers(),
            'percentBrigadier' => $this->getPercentBrigadier(),
            'color' => $this->getColor(),
            'created' => $this->getCreated()->getTimestamp()*1000
        );
    }

    public static function getProjects($em, $parameters, $inArray = false)
    {
        $qb = $em->getRepository("RenovateMainBundle:Project")
            ->createQueryBuilder('p');

        $qb->select('p')
            ->addOrderBy('p.created', 'DESC');

        if (isset($parameters['offset']) && isset($parameters['limit']))
        {
            $qb->setFirstResult($parameters['offset'])
                ->setMaxResults($parameters['limit']);
        }

        if (isset($parameters['search']))
        {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.name', $qb->expr()->literal('%'.$parameters['search'].'%'))
            ));
        }

        $result = $qb->getQuery()->getResult();

        if ($inArray)
        {
            return array_map(function($project){
                return $project->getInArray();
            }, $result);
        }else return $result;
    }

    public static function getProjectsCount($em, $parameters)
    {
        $qb = $em->getRepository("RenovateMainBundle:Project")
            ->createQueryBuilder('p');

        $qb->select('COUNT(p.id)');

        if (isset($parameters['search']))
        {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.name', $qb->expr()->literal('%'.$parameters['search'].'%'))
            ));
        }

        $total = $qb->getQuery()->getSingleScalarResult();

        return $total;
    }

    public static function addProject($em, $parameters)
    {
        $project = new Project();
        $project->setName($parameters->name);
        $project->setBudget($parameters->budget);
        $project->setTime($parameters->time);
        $project->setPercentWorkers($parameters->percentWorkers);
        $project->setPercentBrigadier($parameters->percentBrigadier);
        $project->setColor($parameters->color);
        $project->setCreated(new \DateTime());

        $em->persist($project);
        $em->flush();

        return $project;
    }

    public static function removeProjectById($em, $id)
    {
        $project = $em->getRepository("RenovateMainBundle:Project")->find($id);
        foreach($project->getEvents() as $event){
            $em->remove($event);
        }

        $em->persist($project);
        $em->flush();
        $em->remove($project);
        $em->flush();

        return true;
    }

    public static function editProjectById($em, $id, $parameters)
    {
        $project = $em->getRepository("RenovateMainBundle:Project")->find($id);

        $project->setName($parameters->name);
        $project->setBudget($parameters->budget);
        $project->setTime($parameters->time);
        $project->setPercentWorkers($parameters->percentWorkers);
        $project->setPercentBrigadier($parameters->percentBrigadier);
        $project->setColor($parameters->color);

        $em->persist($project);
        $em->flush();

        return $project;
    }
}
