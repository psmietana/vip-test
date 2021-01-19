<?php

namespace App\Controller\User;

use App\Commands as Commands;
use App\Queries\GetBooksForUserQuery;
use Doctrine\DBAL\Connection;
use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $commandBus;
    private $connection;

    public function __construct(CommandBus $commandBus, Connection $connection)
    {
        $this->commandBus = $commandBus;
        $this->connection = $connection;
    }

    /**
     * @Route("/", methods={"POST"}, name="app_user_add")
     */
    public function addUser(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\AddUserCommand(
                $request->get('firstName'),
                $request->get('lastName'),
                (string) $request->get('email'),
                $request->get('phoneNumber')
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
     * @Route("/", methods={"PUT"}, name="app_user_edit")
     */
    public function editUser(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\EditUserCommand(
                (int) $request->get('id'),
                $request->get('firstName'),
                $request->get('lastName'),
                (string) $request->get('email'),
                $request->get('phoneNumber')
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
     * @Route("/", methods={"DELETE"}, name="app_user_delete")
     */
    public function deleteUser(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\DeleteUserCommand((int) $request->get('id')));
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
     * @Route("/get-books", methods={"GET"}, name="app_user_get_books")
     */
    public function getBooks(Request $request): JsonResponse
    {
        try {
            $data = $this->commandBus->handle(new GetBooksForUserQuery((int) $request->get('id')));

            return new JsonResponse($data, 200);
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 400);
        } catch (Exception $exception) {
            return new JsonResponse(['message' => 'Internal error'], 500);
        }
    }
}
