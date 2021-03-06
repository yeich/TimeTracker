<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectPriority;
use App\Entity\ProjectState;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('description', TextType::class, [
                'required' => false,
            ])
            ->add('hours_planned', IntegerType::class, [
                'required' => true,
            ])
            ->add('management', EntityType::class, [
                'class' => User::class,
                'required' => true,
                'choice_label' => function(User $user) {
                    return sprintf('%s %s (%s)', $user->getFirstname(), $user->getLastname(), $user->getEmail());
                }
            ])
            ->add('workers', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'required' => true,
                'choice_label' => function(User $user) {
                    return sprintf('%s %s (%s)', $user->getFirstname(), $user->getLastname(), $user->getEmail());
                }
            ])
            ->add('projectPriority', EntityType::class, [
                'class' => ProjectPriority::class,
                'required' => true,
                'choice_label' => function(ProjectPriority $projectPriority) {
                    return sprintf('%s', $projectPriority->getName());
                }
            ])
            ->add('deadline')
//            ->add('hourly_rate')
            ->add('state', EntityType::class, [
                'class' => ProjectState::class,
                'required' => true,
                'choice_label' => function(ProjectState $projectState) {
                    return sprintf('%s', $projectState->getName());
                }
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
