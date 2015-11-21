<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class AgreementLifeType extends AbstractType
{
    /**
     * @var User
     */
    private $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', null, [
                'label' => 'agreement.agrValue',
            ])
            ->add('person', null, [
                'label' => 'agreement.insuredClient',
            ])
            
            ->add('client', 'entity', [
                'label' => 'agreement.client',
                'class' => 'AppBundle\Entity\UserDetailsClient',
                'property' => 'fullName',
                'query_builder' => function(EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('c');
                },
            ])
            
            ->add('attachments', 'collection', [
                'label' => 'agreement.attachment',
                'type' => new AttachmentType(),
            ])
        ;
        
        if ($this->user->hasRole('ROLE_MANAGER')) {
            $builder
                ->add('agent', 'entity', [
                    'label' => 'agreement.agent',
                    'class' => 'AppBundle\Entity\UserDetailsAgent',
                    'property' => 'fullName',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->getAgentsQueryBuilder($this->user);
                    },
                ]);
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\AgreementLife',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'agreementlife';
    }
}
