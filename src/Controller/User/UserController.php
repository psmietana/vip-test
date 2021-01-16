<?php

namespace App\Controller\User;

use App\Commands\AddUserCommand;
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
            $this->commandBus->handle(new AddUserCommand(
                $request->get('firstName'),
                $request->get('lastName'),
                $request->get('email')
            ));
            $this->connection->commit();

            return new JsonResponse(null, 201);
        } catch (InvalidArgumentException $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => 'Internal error'], 400);
        } catch (Exception $exception) {
            $this->connection->rollBack();

            return new JsonResponse(['message' => 'Internal error'], 500);
        }
    }
}
