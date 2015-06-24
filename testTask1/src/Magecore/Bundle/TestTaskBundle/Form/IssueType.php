<?php

namespace Magecore\Bundle\TestTaskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('summary')
            ->add('description')
            //->add('created')
            //->add('updated')
            //->add('reporter')
            ->add('assignee')
        ;
        if (isset($options['data'])){

            if (get_class($options['data']) == 'Magecore\Bundle\TestTaskBundle\Entity\Issue'){
                if ($options['data']->getId() == 0) {
                    $arr = $options['data']->getParentTypes();
                    $arr = array_combine($arr, $arr);
                }
            }

        }
        if (!empty($arr)){
            $builder->add('type','choice', array(
                'choices' => array(
                    $arr
                ),
                'required' => true,
          ))
            ;
        }
        $builder
            ->add('priority')
            ->add('status')
            ->add('resolution')
            ->add('parentIssue')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magecore\Bundle\TestTaskBundle\Entity\Issue'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'magecore_bundle_testtaskbundle_issue';
    }
}
