<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TagController.
 */
class TagController extends Controller
{
    /**
     * @var null|TagRepository
     */
    private $tagRepository = null;

    /**
     * TagController constructor.
     *
     * @param TagRepository $tagRepository
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * @Route("/tags", name="tag_list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function list()
    {
        return $this->render('tag/list.html.twig', [
            'tags' => $this->tagRepository->findAllEvenHidden()
        ]);
    }

    /**
     * @Route("/tag/{slug}", name="tag_tag")
     *
     * @ParamConverter("tag", class="App\Entity\Tag")
     *
     * @param Tag $tag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tag(Tag $tag)
    {
        return $this->render('tag/tag.html.twig', [
            'tag' => $tag,
            'posts' => $tag->getPosts()
        ]);
    }
}