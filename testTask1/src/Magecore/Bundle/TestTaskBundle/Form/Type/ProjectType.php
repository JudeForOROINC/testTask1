<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 17.06.15
 * Time: 17:23
 */
namespace Magecore\Bundle\TestTaskBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label','text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max'=>100,'maxMessage'=>'Project Label cannot be longer than {{ limit }} characters!')),
                ),

            ))

            ->add('code','text',array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max'=>3,'maxMessage'=>'Project Label cannot be longer than {{ limit }} characters!')),
                ),
            ))
            ->add('summary','textarea')
            ->add('save','submit',array('label'=>'Create task'))
        ;
    }

    public function getName()
    {
        return 'project';
    }
}