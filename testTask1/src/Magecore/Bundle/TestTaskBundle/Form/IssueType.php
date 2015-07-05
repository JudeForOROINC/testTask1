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
            //->add('code')
            ->add('summary',null,array('label'=>'field.summary.issue'))
            ->add('description',null,array('label'=>'field.description'))
            //->add('created')
            //->add('updated')
            //->add('reporter')
            ->add('assignee',null,array('label'=>'field.assigned'))
        ;
        if (isset($options['data'])){

            if (get_class($options['data']) == 'Magecore\Bundle\TestTaskBundle\Entity\Issue'){
                if ($options['data']->getId() == 0 ) {

                    if ( !empty( $options['data']->getParentIssue() ) ){
                        //do nothing.
                        $arr = null;
                        //var_dump($arr);
                        //$subtask=1;
                    }else {
                        $arr = $options['data']->getParentTypes();
                        $arr = array_combine($arr, $arr);
                    }
                }
            }

        }


        if (!empty($arr)){
            $builder->add('type','choice', array(
                'choices' => array(
                    $arr
                ),
                'required' => true,
                'label'=>'field.type'
          ))
            ;
        }
        $builder
            ->add('priority',null,array('label'=>'field.priority'))
            ->add('status',null,array('label'=>'field.status'))
            ->add('resolution',null,array('label'=>'field.resolution'))
            //->add('parentIssue')
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
