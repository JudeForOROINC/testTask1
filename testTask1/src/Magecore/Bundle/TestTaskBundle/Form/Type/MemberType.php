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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\ImageValidator;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname','text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max'=>100,'maxMessage'=>'Project Label cannot be longer than {{ limit }} characters!')),
                ),

            ))

            ->add('timezone','timezone',array(
                'constraints' => array(
                    new NotBlank(),
                ),
            ))
            ->add('file','file',array('constraints' => array(
                new Image(),
                ),
                //'empty_data'=>null,
                'required' => false,
            ))
            ->add('remove_ava','checkbox',array(
                'label'=>'I want to remove my avatar.',
                'required' => false,
            ))
            ->add('save','submit',array('label'=>'Save user'))
        ;
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Magecore\Bundle\TestTaskBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'user';
    }
}