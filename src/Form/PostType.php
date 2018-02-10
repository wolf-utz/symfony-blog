<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PostType.
 */
class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('tags', TextType::class, ['attr' => ['class' => 'form-control', 'data-role' => 'tagsinput'], 'label' => 'Enter some tags'])
            ->add('body', TextareaType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-primary btn-lg'], 'label' => '+ Create Post'])
        ;

        $builder->get('tags')->addModelTransformer($this->getTagsCallbackTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Post::class,
        ));
    }

    /**
     * @return CallbackTransformer
     */
    protected function getTagsCallbackTransformer()
    {
        return new CallbackTransformer(
            /** @param  ArrayCollection|null $tagsAsArrayCollection */
            function ($tagsAsArrayCollection) {
                if(!$tagsAsArrayCollection instanceof ArrayCollection || $tagsAsArrayCollection->count() <= 0 ) {
                    return;
                }
                $titles = [];
                /** @var Tag $tag */
                foreach($tagsAsArrayCollection as $tag) {
                    $titles[] = $tag->getTitle();
                }

                if(!is_array($titles) || count($titles) <= 0) {
                    return "";
                }
                // transform the array to a string
                return implode(', ', $titles);
            },
            /** @param  string|null $tagsAsString */
            function (string $tagsAsString) {
                $tags = new ArrayCollection();
                foreach(explode(',', $tagsAsString) as $title) {
                    $tag = new Tag();
                    $tag->setTitle(trim($title));
                    $tags->add($tag);
                }
                return $tags;
            }
        );
    }
}
