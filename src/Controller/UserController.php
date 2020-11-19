<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserAuthType;
use App\DTO\UserDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractApiController 
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(): Response
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        $usersList = $userRepository->findAll();
        
        $usersListDTO = UserDTO::usersListDTO($usersList);
        
        return $this->respond($usersListDTO);
    }

    /**
     * @Route("/new", name="user_new", methods={"POST"})
     */
    public function new(Request $request) {

        $user = new User();

        $form = $this->buildForm(UserType::class, $user);
        
        $form->handleRequest($request);//$form->submit($request->request->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $userRepository = $this->getDoctrine()->getRepository(User::class);
            if($userRepository->findOneByUsername($user->getUsername())) {                
                
                return $this->respond(
                    $form->getErrors(),
                    Response::HTTP_UNAUTHORIZED,
                    "Username alredy exists"
                );
            }
            else {

                $userRepository->encodePassword($user);
                $userRepository->save($user);
                
                return $this->respond(
                    new UserDTO($user),
                    Response::HTTP_OK
                );
            }
        }
        else {

            return $this->respond(
                $form->getErrors(),
                Response::HTTP_UNAUTHORIZED,
                "Invalid Fields");                
        }
    }

    /**
     * @Route("/edit", name="user_edit", methods={"POST","PUT"})
     */
    public function edit(Request $request): Response
    {
        $user = new User();

        $form = $this->buildForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->respond(
                new UserDTO($user),
                Response::HTTP_OK
            );
        }
        else {

            return $this->respond(
                $form->getErrors(),
                Response::HTTP_UNAUTHORIZED,
                "Invalid Fields");
        }
    }
    
    /**
     * @Route("/auth", methods={"POST"})
     */
    public function auth(Request $request)
    { 
        $user = new User();

        $form = $this->buildForm(UserAuthType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $teste = $form->isValid()) {
                
            $userRepository = $this->getDoctrine()->getRepository(User::class);

            if($userResult = $userRepository->auth($user)) {
                
                return $this->respond(
                    new UserDTO($userResult),
                    Response::HTTP_OK
                );            
            }
            else
            {
                return $this->respond(
                    $form->getErrors(),
                    Response::HTTP_UNAUTHORIZED,
                    "Username or Password incorrect");
            }
        }
        else {

            return $this->respond(
                $form->getErrors(),
                Response::HTTP_UNAUTHORIZED,
                "Invalid Fields");
        }
    }
}