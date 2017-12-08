<?php

namespace OC\ChatBundle\Controller;

use OC\ChatBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class SalonController extends Controller
{
    public function indexAction(Request $request)
    {

        $commentaire = new Commentaire();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $commentaire);

        $formBuilder
            ->add('date', DateType::class, array(
                'format' => 'yyyy-MM-dd'
            ))
            ->add('author', TextareaType::class)
            ->add('comment', TextareaType::class)
            ->add('poster', SubmitType::class);

        $form = $formBuilder->getForm();

        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
        }

        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('oc_chat_homepage');
        }


        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCChatBundle:Commentaire')
            ;
        $listComments = $repository->findAll();

        return $this->render('OCChatBundle::index.html.twig', array('listComments' => $listComments, 'form' => $form->createView(),));
    }
}
