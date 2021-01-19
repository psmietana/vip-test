<?php

namespace App\Controller\Book;

use App\Commands as Commands;
use App\Queries\SearchBookQuery;
use Doctrine\DBAL\Connection;
use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    private $commandBus;
    private $connection;

    public function __construct(CommandBus $commandBus, Connection $connection)
    {
        $this->commandBus = $commandBus;
        $this->connection = $connection;
    }

    /**
     * @Route("/", methods={"POST"}, name="app_book_add")
     */
    public function addBook(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\AddBookCommand(
                $request->get('title'),
                $request->get('description'),
                $request->get('shortDescription')
            ));
            $this->connection->commit();

            return new JsonResponse(null, 201);
        } catch (InvalidArgumentException $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => $exception->getMessage()], 400);
        } catch (Exception $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => 'Internal error'], 500);
        }
    }

    /**
     * @Route("/", methods={"PUT"}, name="app_book_edit")
     */
    public function editBook(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\EditBookCommand(
                (int) $request->get('id'),
                $request->get('title'),
                $request->get('description'),
                $request->get('shortDescription')
            ));
            $this->connection->commit();

            return new JsonResponse(null, 204);
        } catch (InvalidArgumentException $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => $exception->getMessage()], 400);
        } catch (Exception $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => 'Internal error'], 500);
        }
    }
    /**
     * @Route("/", methods={"DELETE"}, name="app_book_delete")
     */
    public function deleteBook(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\DeleteBookCommand((int) $request->get('id')));
            $this->connection->commit();

            return new JsonResponse(null, 204);
        } catch (InvalidArgumentException $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => $exception->getMessage()], 400);
        } catch (Exception $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => 'Internal error'], 500);
        }
    }

    /**
     * @Route("/assign-to-user", methods={"PATCH"}, name="app_book_assign_to_user")
     */
    public function assignToUser(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\AssignBookToUserCommand(
                (int) $request->get('bookId'),
                (int) $request->get('userId')
            ));
            $this->connection->commit();

            return new JsonResponse(null, 204);
        } catch (InvalidArgumentException $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => $exception->getMessage()], 400);
        } catch (Exception $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => 'Internal error'], 500);
        }
    }

    /**
     * @Route("/search", methods={"GET"}, name="app_book_search")
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $data = $this->commandBus->handle(new SearchBookQuery(
                (string) $request->get('query'),
                (bool) $request->get('in_description')
            ));

            return new JsonResponse($data, 200);
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 400);
        } catch (Exception $exception) {
            return new JsonResponse(['message' => 'Internal error'], 500);
        }
    }
}
