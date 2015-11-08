<?php namespace AdminBundle\Controller;

use AdminBundle\Form\ProductType;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $product->setAmount(0);
        
        $form = $this->createForm(new ProductType(), $product);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            
            $this->addFlash('success', 'Produkt został pomyślnie dodany.');
            
            return $this->redirectToRoute('admin_product_list');
        }
        
        return $this->render('AdminBundle:Product:new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}")
     */
    public function editAction(Product $product, Request $request)
    {
        $form = $this->createForm(new ProductType(), $product);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $this->addFlash('success', 'Produkt został pomyślnie zaktualizowany.');
            
            return $this->redirectToRoute('admin_product_list');
        }
        
        return $this->render('AdminBundle:Product:edit.html.twig',[
            'form' => $form->createView()
        ]);
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
