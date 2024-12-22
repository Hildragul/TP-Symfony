<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le titre de l\'article',
                ],
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label font-weight-bold text-primary'
                ],
                'row_attr' => [
                    'class' => 'mb-4',
                ],
            ])
            ->add('texte', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Écrivez le contenu de l\'article ici',
                    'rows' => 6,
                ],
                'label' => 'Contenu',
                'label_attr' => [
                    'class' => 'form-label font-weight-bold text-primary'
                ],
                'row_attr' => [
                    'class' => 'mb-4',
                ],
            ])
            ->add('publie', null, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Publier l\'article',
                'label_attr' => [
                    'class' => 'form-check-label font-weight-bold text-secondary'
                ],
                'row_attr' => [
                    'class' => 'mb-4',
                ],
            ])
            ->add('date', null, [
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control date-picker',
                    'placeholder' => 'Sélectionnez la date',
                ],
                'label' => 'Date de publication',
                'label_attr' => [
                    'class' => 'form-label font-weight-bold text-primary'
                ],
                'row_attr' => [
                    'class' => 'mb-4',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Téléchargez une image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG/PNG).',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-lg btn-success btn-block',
                    'style' => 'font-size: 1.1rem;',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
