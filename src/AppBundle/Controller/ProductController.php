<?php namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{

    /**
     * @Route("/list", name="product_list")
     */
    public function listAction(Request $request)
    {
//        $qb = $this->getDoctrine()
//            ->getRepository('AppBundle:Product')
//            ->createQueryBuilder('p')
//            ->select(['p', 'c'])
//            ->innerJoin('p.category', 'c');
        
        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->from('AppBundle:Product', 'p')
            ->select(['p', 'c'])
            ->innerJoin('p.category', 'c');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb, 
            $request->query->getInt('page', 1)/* page number */,
            25/* limit per page */
        );

        return $this->render('product/list.html.twig', [
            'products' => $pagination
        ]);
    }

    /**
     * @Route("/{id}/add-to-cart", name="product_add_to_cart")
     * @Template()
     */
    public function addToCartAction(Product $product = null)
    {
        // alternatywna wersja bez wymuszania typu z argumencie metody
//        $product = $this->getDoctrine()
//            ->getRepository('AppBundle:Product')
//            ->find($id);
        
        if (!$product) {
            throw $this->createNotFoundException("Produkt nie znaleziony!");
        }

        $this->getBasket()
            ->add($product);

        $this->addFlash('success', 'Produkt został pomyślnie dodany do koszyka.');

        return $this->redirectToRoute('product_basket');
    }

    /**
     * @Route("/basket", name="product_basket")
     */
    public function basketAction()
    {
        return $this->render('product/basket.html.twig', [
                'basket' => $this->getBasket()
        ]);
    }

    /**
     * @Route("/{id}/remove-from-cart", name="product_basket_remove")
     */
    public function removeFromCartAction(Product $product)
    {
        try {
            $this->getBasket()
                ->remove($product);
            
            $this->addFlash('success', 'Produkt został pomyślnie usunięty z koszyka.');
            
        } catch (\AppBundle\Exception\ProductNotFoundException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('product_basket');
    }

    /**
     * @Route("/clear-basket", name="product_basket_clear")
     */
    public function clearBasketAction()
    {
        $this->getBasket()
            ->clear();
        
        return $this->redirectToRoute('product_basket');
    }
    
    public function basketBoxAction()
    {
        return $this->render('product/basketBox.html.twig', [
            'basket' => $this->getBasket()
        ]);
    }
    
    /**
     * @return \AppBundle\Utils\Basket
     */
    private function getBasket()
    {
        return $this->get('basket');
    }
}
