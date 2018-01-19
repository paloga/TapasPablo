<?php

namespace RestauranteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RestauranteBundle\Entity\Tapas;
use RestauranteBundle\Form\TapasType;
use RestauranteBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use RestauranteBundle\Entity\User;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
      $repository = $this->getDoctrine()->getRepository(Tapas::class);
      $tapas = $repository->findAll();
        return $this->render('RestauranteBundle:Default:index.html.twig', array('tapas' => $tapas));
    }
    /**
     * @Route("/insertar", name="insertar")
     */
    public function insertarAction(Request $request)
    {
      $tapa = new Tapas();
      $form = $this -> createForm(TapasType::Class, $tapa);
      $form->handleRequest($request);
      if ($form -> isSubmitted()&& $form -> isValid()){
        $tapa = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em -> persist($tapa);
        $em -> flush();
        return $this->redirectToRoute('home');
      }
      return $this->render('RestauranteBundle:Default:insertar.html.twig', array('form' => $form -> createView()));
    }
    /**
     * @Route("/eliminar/{id}", name="eliminar")
     */
    public function eliminarAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $repository = $this->getDoctrine()->getRepository(Tapas::class);
      $tapa = $repository->find($id);
      $em->remove($tapa);
      $em->flush();
      return $this->redirectToRoute('home');
    }

    /**
     * @Route("/registro", name="registro")
     */
    public function registroAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render('RestauranteBundle:Default:registro.html.twig', array('form' => $form -> createView()));
    }
}
