<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 17.06.15
 * Time: 17:23
 */
namespace MagecoreTestTaskBundle\Form\Type;


class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label','text')
            ->add('summary', null, array('widget' => 'single_text'))
            ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'project';
    }
}