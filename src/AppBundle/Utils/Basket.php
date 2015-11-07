<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Utils;

use AppBundle\Entity\Product;
use AppBundle\Exception\ProductNotFoundException;

/**
 * Description of Basket
 *
 * @author kamil
 */
class Basket
{
    protected $session;
    
    public function __construct($session)
    {
        $this->session = $session;
    }
    
    /**
     * @param Product $product
     * @return \AppBundle\Utils\Basket
     */
    public function add(Product $product)
    {
        // koszyk
        $basket = $this->session->get('basket', []);

        if (!array_key_exists($product->getId(), $basket)) {
            $basket[$product->getId()] = [
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'quantity' => 1
            ];
        } else {
            $basket[$product->getId()]['quantity'] ++;
        }

        // aktualizujemy koszyk
        $this->session->set('basket', $basket);
        
        return $this;
    }
    
    /**
     * Usuwa produkt z koszyka
     * 
     * @param Product $product
     * @return \AppBundle\Utils\Basket
     * @throws ProductNotFoundException
     */
    public function remove(Product $product)
    {
        $basket = $this->session->get('basket', []);
        
        if (!array_key_exists($product->getId(), $basket)) {
            throw new ProductNotFoundException("Produkt nie znajduje się w koszyku.");
        }
        
        unset($basket[$product->getId()]);
        
        // aktualizujemy koszyk
        $this->session->set('basket', $basket);
        
        return $this;
    }
    
    /**
     * @return \AppBundle\Utils\Basket
     */
    public function clear()
    {
        // aktualizujemy koszyk
        $this->session->set('basket', []);
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->session->get('basket', []);
    }

}
