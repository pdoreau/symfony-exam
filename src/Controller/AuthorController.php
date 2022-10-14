<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\Type\Author as AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorController extends AbstractController
{
    private AuthorRepository $authorRepository;
    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    /**
     * @param AuthorRepository       $authorRepository
     * @param EntityManagerInterface $em
     * @param SerializerInterface    $serializer
     */
    public function __construct(AuthorRepository $authorRepository, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->authorRepository = $authorRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    #[Route('/authors', methods: ['GET'], format: 'json')]
    #[OA\Parameter(name: 'search', in: 'query')]
    #[OA\Response(
        response: 200,
        description: 'Return the list of authors',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Author::class, groups: ['author-list']))
        )
    )]
    public function list(Request $request)
    {
        return JsonResponse::fromJsonString($this->serializer->serialize(
            $this->authorRepository->findByLastNameOrFirstName($request->query->get('search')),
            'json',
            (new ObjectNormalizerContextBuilder())->withGroups('author-list')->toArray())
        );
    }

    #[Route('/authors', methods: ['POST'], format: 'json')]
    #[OA\RequestBody(content:new OA\JsonContent(ref: new Model(type: AuthorType::class)))]
    #[OA\Response(
        response: 200,
        description: 'The reference of the created resource',
        content: new OA\JsonContent(ref: new Model(type: Author::class, groups: ['author-post']))
    )]
    public function post(Request $request)
    {
        $form = $this->createForm(AuthorType::class, $author = new Author());
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            return new JsonResponse([
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ],
                400
            );
        }

        // Could be avoided if we consider attaching a book to an author
        $author->updateBooksAuthor();

        $this->em->persist($author);
        $this->em->flush();

        return new JsonResponse(['id' => $author->getId()]);
    }

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
