<?php

namespace AbreNTech\AbtBundle\Entity;

use AbreNTech\AbtBundle\Entity\Category;
use AbreNTech\AbtBundle\Entity\Type;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AbreNTech\AbtBundle\Repository\LinkRepository")
 * @ORM\Table(name="LINK")
 */
class Link
{
    // internal properties
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=75)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $linkstr;

    /**
     * @return string
     */
    public function getLinkstr()
    {
        return $this->linkstr;
    }

    /**
     * @param string $linkstr
     */
    public function setLinkstr($linkstr)
    {
        $this->linkstr = $linkstr;
    }

    /**
     * @return \AbreNTech\AbtBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param \AbreNTech\AbtBundle\Entity\Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    // external properties
    /**
     * @var Type
     *
     * @ORM\ManyToOne(targetEntity="Type", inversedBy="links")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="links")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return \AbreNTech\AbtBundle\Entity\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \AbreNTech\AbtBundle\Entity\Type $type
     */
    public function setType(Type $type)
    {
        $this->type = $type;
    }

}