<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 12.07.15
 * Time: 19:31
 */
namespace Magecore\Bundle\TestTaskBundle\Command;

use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
//use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Input\InputArgument;
//use Symfony\Component\Console\Input\InputOption;
//use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('my:user:create')
            ->setDescription('Create new User')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputArgument('fullname', InputArgument::REQUIRED, 'The Full name'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
            ))
            ->setHelp(<<<EOT
The <info>my:user:create</info> command creates a user:

  <info>php app/console my:user:create matthieu</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password and fullname as the second and third and forth arguments:

  <info>php app/console my:user:create matthieu matthieu@example.com mypassword fullname</info>

You can create a super admin via the super-admin flag:

  <info>php app/console my:user:create admin --super-admin</info>

You can create an inactive user (will not be able to log in):

  <info>php app/console my:user:create thibault --inactive</info>

EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username   = $input->getArgument('username');
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');
        $fullname   = $input->getArgument('fullname');
        $inactive   = $input->getOption('inactive');
        $superadmin = $input->getOption('super-admin');





        //$manager->persist($johnUser);


        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $q = $em->createQueryBuilder();
        $q->select('u')->from('MagecoreTestTaskBundle:User', 'u')
            ->where('u.username = :username')
            ->setParameter('username', strtolower($username));

        $users = $q->getQuery()->getResult();

        if ($users) {
            throw new \Exception('user already exists');
        }
        $q = $em->createQueryBuilder();
        $q->select('u')->from('MagecoreTestTaskBundle:User', 'u')
            ->where('u.email = :mail')
            ->setParameter('mail', strtolower($email));

        $users = $q->getQuery()->getResult();

        if ($users) {
            throw new \Exception('Mail is forbiden. chouse enother one.');
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setFullName($fullname);
        $passwordEncoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($user);
        $encodedPassword = $passwordEncoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);


        if (!$inactive) {
            $user->setEnabled(true);
        }

        if (!$superadmin) {
            $user->setRole($user::ADMINISTRATOR);
        } else {
            $user->setRole($user::OPERATOR);
        }
        $em->persist($user);
        $em->flush();



        //$manipulator->create($username, $password, $email, !$inactive, $superadmin);
        //$user->setEmail($email);


        $output->writeln(sprintf('Created user <comment>%s</comment> for fullname <info>%s</info>',
            $username, $fullname));
    }

//    /**
//     * @see Command
//     */
//    protected function interact(InputInterface $input, OutputInterface $output)
//    {
//        if (!$input->getArgument('username')) {
//            $username = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Please choose a username:',
//                function ($username) {
//                    if (empty($username)) {
//                        throw new \Exception('Username can not be empty');
//                    }
//
//                    return $username;
//                }
//            );
//            $input->setArgument('username', $username);
//        }
//
//        if (!$input->getArgument('email')) {
//            $email = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Please choose an email:',
//                function($email) {
//                    if (empty($email)) {
//                        throw new \Exception('Email can not be empty');
//                    }
//
//                    return $email;
//                }
//            );
//            $input->setArgument('email', $email);
//        }
//
//        if (!$input->getArgument('password')) {
//            $password = $this->getHelper('dialog')->askHiddenResponseAndValidate(
//                $output,
//                'Please choose a password:',
//                function($password) {
//                    if (empty($password)) {
//                        throw new \Exception('Password can not be empty');
//                    }
//
//                    return $password;
//                }
//            );
//            $input->setArgument('password', $password);
//        }
//
//        if (!$input->getArgument('fullname')) {
//            $fullname = $this->getHelper('dialog')->askAndValidate(
//                $output,
//                'Please choose a fullname:',
//                function($fullname) {
//                    if (empty($fullname)) {
//                        throw new \Exception('Fullname can not be empty');
//                    }
//
//                    return $fullname;
//                }
//            );
//            $input->setArgument('fullname', $fullname);
//        }
//    }

}


