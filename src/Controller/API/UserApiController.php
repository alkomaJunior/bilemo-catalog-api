<?php

namespace App\Controller\API;

use App\Entity\Resource\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;

#[Route(path: "/api")]
class UserApiController extends AbstractFOSRestController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Rest\Get(
     *     path="/users",
     *     name="api_user_list"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="1"
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1"
     * )
     * @Rest\View(serializerGroups={ "Default", "items"="listUser" })
     */
    public function listUsers(ParamFetcherInterface $paramFetcher): View
    {
         $users = $this->userRepository->paginatedUsers($paramFetcher->get('limit'), $paramFetcher->get('page'));

        return View::create()
            ->setStatusCode(200)
            ->setFormat("json")
            ->setData(["code" => "Success!", "data" => $users]);
    }

    /**
     * @Rest\Get(
     *     path="/users/{id}",
     *     name="api_user_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View(serializerGroups={ "Default", "items"="showUser" })
     */
    public function showUser(User $user): View
    {
        return View::create()
            ->setStatusCode(200)
            ->setFormat("json")
            ->setData(["code" => "Success!", "data" => $user]);
    }

    /**
     * @Rest\Post(
     *     path="/users",
     *     name="api_user_create"
     * )
     * @Rest\View(statusCode=201, serializerGroups={ "Default", "items"="showUser" })
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *          "validator"={ "groups"="createUser" }
     *     }
     * )
     */
    public function createUsers(
        User $user,
        ConstraintViolationList $violations,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ): View {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf(
                    "Field %s: %s | ",
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                );
            }
            throw new BadRequestHttpException($message);
        }

        $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $data = $userRepository->findOneBy(['id' => $user->getId()]);
        return View::create()
            ->setStatusCode(201)
            ->setFormat("json")
            ->setData(["code" => "Created!", "data" => $data]);
    }

    /**
     * @Rest\View(StatusCode = 204)
     * @Rest\Delete(
     *     path = "/users/{id}",
     *     name = "api_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function deleteUser(User $user)
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return;
    }
}
