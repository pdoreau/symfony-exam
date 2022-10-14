<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends AbstractController
{
    private EntityManagerInterface $em;
    private BookRepository $bookRepository;
    private SerializerInterface $serializer;

    /**
     * @param EntityManagerInterface $em
     * @param BookRepository         $bookRepository
     * @param Serializer             $serializer
     */
    public function __construct(EntityManagerInterface $em, BookRepository $bookRepository, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->bookRepository = $bookRepository;
        $this->serializer = $serializer;
    }

    #[Route('/books', methods: ['GET'], format: 'json')]
    #[OA\Response(
        response: 200,
        description: 'Return the list of books',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Book::class, groups: ['book-list']))
        )
    )]
    public function books()
    {
        return JsonResponse::fromJsonString($this->serializer->serialize(
            $this->bookRepository->findAll(),
            'json',
            (new ObjectNormalizerContextBuilder())->withGroups('book-list')->toArray())
        );
    }

    #[Route('/books/add-suffix', methods: ['POST'], format: 'json')]
    #[OA\RequestBody(content: new OA\JsonContent(properties: [new OA\Property(property: 'suffix', type: 'string')]))]
    #[OA\Response(
        response: 200,
        description: 'Iterate over all books and add suffix on titles',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Book::class, groups: ['book-list']))
        )
    )]
    public function addSuffix(Request $request)
    {
        $books = $this->bookRepository->findAll();
        foreach ($books as $book) {
            $book->setTitle(
                $book->getTitle().' - '.json_decode($request->getContent(), true)['suffix']
            );
        }

        $this->em->flush();

        return JsonResponse::fromJsonString($this->serializer->serialize(
            $books,
            'json',
            (new ObjectNormalizerContextBuilder())->withGroups('book-list')->toArray())
        );
    }
}
