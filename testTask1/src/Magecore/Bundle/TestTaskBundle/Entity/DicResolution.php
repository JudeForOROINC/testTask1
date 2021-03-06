<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DicPriority
 *
 * @ORM\Table(name="magecore_testtask_dic_resolution")
 * @ORM\Entity
 */
class DicResolution
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
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


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
     * Set value
     *
     * @param string $value
     * @return DicPriority
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    public function __toString(){
        return (string)$this->getValue();
    }
}
