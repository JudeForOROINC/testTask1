<?php

namespace Magecore\Bundle\TestTaskBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\DependencyInjection\Security\UserProvider\EntityFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;

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

        if(isset($options['projects'])&& count($options['projects'])){
            $pro = $options['projects'];
            $builder->add(
                'project'
            )

        }




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

        //var_dump($options);


        if (!empty($arr)){
            $builder->add('type','choice', array(
                'choices' =>
                    $arr,

                'required' => true,
                'label'=>'field.type',
                'empty_value' => false,
                'empty_data'=>null,
          ))
            ;
        }
        $builder
            ->add('priority',null,array('label'=>'field.priority',
                'class' => 'Magecore\Bundle\TestTaskBundle\Entity\DicPriority',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')->orderBy('p.sortOrder','ASC');
                }
                ))
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
            'data_class' => 'Magecore\Bundle\TestTaskBundle\Entity\Issue',
            'projects' => array(),
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
