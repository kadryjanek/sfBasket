<?php namespace AdminBundle\Controller;

use AdminBundle\Form\CategoryType;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{

    /**
     * @Route("/list/{page}", defaults={"page"=1})
     */
    public function listAction($page)
    {
        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select(['c'])
            ->from('AppBundle:Category', 'c');
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, 20);

        return $this->render('AdminBundle:Category:list.html.twig', [
            'categories' => $pagination
        ]);
    }

    /**
     * @Route("/new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        
        $form = $this->createForm(new CategoryType(), $category);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            $this->addFlash('success', 'Kategoria został pomyślnie dodana.');
            
            return $this->redirectToRoute('admin_category_list');
        }
        
        return $this->render('AdminBundle:Category:new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}")
     * @Template()
     */
    public function editAction($id)
    {
        return array(
            // ...
        );
    }

    /**
     * @Route("/delete")
     * @Template()
     */
    public function deleteAction()
    {
        return array(
            // ...
        );
    }
}
