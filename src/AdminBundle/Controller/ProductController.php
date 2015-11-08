<?php namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{

    /**
     * @Route("/list/{page}", defaults={"page"=1})
     */
    public function listAction($page)
    {
        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->from('AppBundle:Product', 'p')
            ->select(['p', 'c'])
            ->innerJoin('p.category', 'c');
        
        // $products = $qb->getQuery()->getResult();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, 20);

        return $this->render('AdminBundle:Product:list.html.twig', [
            'products' => $pagination
        ]);
    }

    /**
     * @Route("/new")
     * @Template()
     */
    public function newAction()
    {
        return array(
            // ...
        );
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
     * @Route("/delete/{id}")
     * @Template()
     */
    public function deleteAction($id)
    {
        return array(
            // ...
        );
    }
}
