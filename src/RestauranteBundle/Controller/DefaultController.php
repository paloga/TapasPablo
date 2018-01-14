<?php

namespace RestauranteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RestauranteBundle\Entity\Tapas;
use RestauranteBundle\Form\TapasType;
use Symfony\Component\HttpFoundation\Request;

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
}
