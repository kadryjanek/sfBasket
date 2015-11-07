<?php namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{

    /**
     * @Route("/list", name="product_list")
     */
    public function listAction()
    {
        $products = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->findAll();
        
        return $this->render('product/list.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/{id}/add-to-cart", name="product_add_to_cart")
     * @Template()
     */
    public function addToCartAction(Product $product)
    {
//        $product = $this->getDoctrine()
//            ->getRepository('AppBundle:Product')
//            ->find($id);
        
//        if (!$product) {
//            throw $this->createNotFoundException("Produkt nie znaleziony!");
//        }
        
        // sesja
        $session = $this->get('session');
        // koszyk
        $basket = $session->get('basket', []);
        
        if (!array_key_exists($product->getId(), $basket)) {
            $basket[$product->getId()] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => 1
            ];
        } else {
            $basket[$product->getId()]['quantity']++;
        }
        
        // aktualizujemy koszyk
        $session->set('basket', $basket);
        
        $this->addFlash('success', 'Produkt został pomyślnie dodany do koszyka.');
        
        return $this->redirectToRoute('product_basket');
    }

    /**
     * @Route("/basket", name="product_basket")
     */
    public function basketAction()
    {
        $products = $this->get('session')->get('basket', []);
        
        return $this->render('product/basket.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/{id}/remove-from-cart")
     * @Template()
     */
    public function removeFromCartAction($id)
    {
        return array(
            // ...
        );
    }

    /**
     * @Route("/clear-basket")
     * @Template()
     */
    public function clearBasketAction()
    {
        return array(
            // ...
        );
    }

}
