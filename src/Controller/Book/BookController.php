<?php

namespace App\Controller\Book;

use App\Commands as Commands;
use Doctrine\DBAL\Connection;
use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/book", methods={"POST"}, name="app_book_add")
     */
    public function addBook(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\AddBookCommand(
                $request->get('firstName'),
                $request->get('lastName'),
                $request->get('email')
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
     * @Route("/book", methods={"PUT"}, name="app_book_edit")
     */
    public function editBook(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\EditBookCommand(
                $request->get('id'),
                $request->get('firstName'),
                $request->get('lastName'),
                $request->get('email')
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
     * @Route("/book", methods={"DELETE"}, name="app_book_delete")
     */
    public function deleteBook(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\DeleteBookCommand($request->get('id')));
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
}
