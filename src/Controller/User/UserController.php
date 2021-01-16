<?php

namespace App\Controller\User;

use App\Commands as Commands;
use Doctrine\DBAL\Connection;
use Exception;
use InvalidArgumentException;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/user", methods={"POST"}, name="app_user_add")
     */
    public function addUser(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\AddUserCommand(
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
     * @Route("/user", methods={"PUT"}, name="app_user_edit")
     */
    public function editUser(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\EditUserCommand(
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
     * @Route("/user", methods={"DELETE"}, name="app_user_delete")
     */
    public function deleteUser(Request $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->commandBus->handle(new Commands\DeleteUserCommand($request->get('id')));
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
