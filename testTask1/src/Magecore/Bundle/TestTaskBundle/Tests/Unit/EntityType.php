<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityType extends AbstractType
{
    /** @var ChoiceList  */
    protected $choiceList = [];

    /** @var string  */
    protected $name;

    /**
     * @param array $choices
     * @param string $name
     */
    public function __construct(array $choices, $name = 'entity')
    {
        $this->name = $name;

        $keys = array_map('strval', array_keys($choices));
        $values = array_values($choices);

        $this->choiceList = new ChoiceList([], []);

        $keysReflection = new \ReflectionProperty(get_class($this->choiceList), 'values');
        $keysReflection->setAccessible(true);
        $keysReflection->setValue($this->choiceList, $keys);

        $valuesReflection = new \ReflectionProperty(get_class($this->choiceList), 'choices');
        $valuesReflection->setAccessible(true);
        $valuesReflection->setValue($this->choiceList, $values);

        $remainingViews = [];
        foreach ($choices as $key => $value) {
            $remainingViews[] = new ChoiceView($value, $key, $key);
        }

        $valuesReflection = new \ReflectionProperty(get_class($this->choiceList), 'remainingViews');
        $valuesReflection->setAccessible(true);
        $valuesReflection->setValue($this->choiceList, $remainingViews);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['choice_list' => $this->choiceList,
            'class'=>'Magecore\Bundle\TestTaskBundle\Entity\User',
            'multiple'=>true,
            'expanded'=>true,
            'query_builder'=>false,
        ]);

    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
