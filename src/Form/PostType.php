<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
     * @var null|TagRepository
     */
    protected $tagRepository = null;

    /**
     * PostType constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
       $this->tagRepository = $tagRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'The title of the post',
                    'attr' => ['class' => 'form-control']
                ]
            )
            ->add(
                'slug',
                TextType::class,
                [
                    'label' => 'The URL slug',
                    'attr' => ['class' => 'form-control', 'placeholder' => '... auto generated ...'],
                    'required' => false
                ]
            )
            ->add(
                'tags',
                TextType::class,
                [
                    'label' => 'Related tags',
                    'attr' => ['class' => 'form-control', 'data-role' => 'tagsinput'],
                    'required' => false
                ]
            )
            ->add(
                'hidden',
                CheckboxType::class,
                [
                    'label' => 'Hide post?',
                    'required' => false
                ]
            )
            ->add(
                'enableComments',
                CheckboxType::class,
                [
                    'label' => 'Enable comments?',
                    'required' => false
                ]
            )
            ->add(
                'teaser',
                TextareaType::class,
                [
                    'label' => 'The teaser (If empty, a crop of the body gets displayed instead)',
                    'attr' => ['class' => 'form-control', 'rows' => 4],
                    'required' => false
                ]
            )
            ->add(
                'body',
                TextareaType::class,
                [
                    'label' => 'The content of the post',
                    'attr' => ['class' => 'rte form-control', 'rows' => 10]
                ]
            )
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
                if(!$tagsAsArrayCollection instanceof \Doctrine\ORM\PersistentCollection) {
                    return "";
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
                    $tag = $this->tagRepository->findOneBy(['title' => trim($title)]);
                    if(is_null($tag)) {
                        $tag = new Tag();
                        $tag->setTitle(trim($title));
                        $tag->generateSlug();
                    }
                    $tags->add($tag);
                }
                return $tags;
            }
        );
    }
}
