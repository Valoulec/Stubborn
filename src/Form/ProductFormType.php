<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints as Assert;

class ProductFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $product = new Product();

        $builder

            ->add('image', FileType::class, [
                'label' => false,
                'data_class' => null,
                'required' => false,
                'mapped' => false,
            ])

            ->add('name', TextType::class, [
                'label' => 'Nom',
                'empty_data' => '',
                'required' => false,
            ])

            ->add('price', NumberType::class, [
                'label' => 'Prix €',
                'scale' => 2,
                'attr' => [
                    'step' => 0.01,
                    'min' => 0,
                ],
                'required' => false,
                'empty_data' => 0.00,
            ])

            ->add('stock', FormType::class, [
                'mapped' => false,
                'label' => false,
            ])


            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
                'attr' => [
                    'class' => 'btn btn-primary mt-3',
                ]
            ])

            ->add('edit', SubmitType::class, [
                'label' => 'Modifier',
                'attr' => [
                    'class' => 'btn btn-warning mt-3',
                ]
            ])


            ->get('stock')
            ->add('XS', IntegerType::class, [
                'label' => 'Stock XS',
                'data' => $product->getStockForSize('XS'),
                'attr' => ['min' => 0],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Ce champ doit être un nombre entier.',
                    ]),
                    new Assert\GreaterThan([
                        'value' => -1,
                        'message' => 'Ce champ doit avoir une valeur positive.'
                    ])
                ],
                'required' => false,
                'empty_data' => '',
            ])
            ->add('S', IntegerType::class, [
                'label' => 'Stock S',
                'data' => $product->getStockForSize('S'),
                'attr' => ['min' => 0],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Ce champ doit être un nombre entier.',
                    ]),
                    new Assert\GreaterThan([
                        'value' => -1,
                        'message' => 'Ce champ doit avoir une valeur positive.'
                    ])
                ],
                'required' => false,
                'empty_data' => '',
            ])
            ->add('M', IntegerType::class, [
                'label' => 'Stock M',
                'data' => $product->getStockForSize('M'),
                'attr' => ['min' => 0],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Ce champ doit être un nombre entier.',
                    ]),
                    new Assert\GreaterThan([
                        'value' => -1,
                        'message' => 'Ce champ doit avoir une valeur positive.'
                    ])
                ],
                'required' => false,
                'empty_data' => '',
            ])
            ->add('L', IntegerType::class, [
                'label' => 'Stock L',
                'data' => $product->getStockForSize('L'),
                'attr' => ['min' => 0],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Ce champ doit être un nombre entier.',
                    ]),
                    new Assert\GreaterThan([
                        'value' => -1,
                        'message' => 'Ce champ doit avoir une valeur positive.'
                    ])
                ],
                'required' => false,
                'empty_data' => '',
            ])
            ->add('XL', IntegerType::class, [
                'label' => 'Stock XL',
                'data' => $product->getStockForSize('XL'),
                'attr' => ['min' => 0],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => 'Ce champ doit être un nombre entier.',
                    ]),
                    new Assert\GreaterThan([
                        'value' => -1,
                        'message' => 'Ce champ doit avoir une valeur positive.'
                    ])
                ],
                'required' => false,
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
