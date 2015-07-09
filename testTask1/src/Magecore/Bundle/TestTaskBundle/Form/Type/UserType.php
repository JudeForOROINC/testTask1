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
    private $admin=false;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname','text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max'=>100,'maxMessage'=>'Project Label cannot be longer than {{ limit }} characters!')),
                ),
                'label'=>'field.fullname',

            ))

            ->add('timezone','timezone',array(
                'constraints' => array(
                    new NotBlank(),
                ),
                'label'=>'field.timezone',
            ));
            if (isset($options['data']) && $this->admin){
                $builder->add(
                    'role','choice',array(
                        'choices' => array(
                            'ROLE_OPERATOR'=>'ROLE_OPERATOR',
                            'ROLE_MANAGER'=>'ROLE_MANAGER',
                            'ROLE_ADMIN'=>'ROLE_ADMIN',
                        ),

                    'required' => false,
                    'multiple' => false,
                    'empty_value' => false,
                    'empty_data' => 'ROLE_OPERATOR',
                    'label' => 'field.role',
                    )
                );
            }


                $builder->add('file','file',array('constraints' => array(
                new Image(),
                ),
                //'empty_data'=>null,
                'required' => false,
                'label'=>'field.file'
            ))
            ->add('remove_ava','checkbox',array(
                'label'=>'action.avatar.remove',
                'required' => false,
            ))
            ->add('save','submit',array('label'=>'button.save.user'))
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

    public function __construct($is_admin = false){
        //parent::__construct();
        $this->admin = $is_admin;
    }
}