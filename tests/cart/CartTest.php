<?php
namespace tests;

use app\exceptions\CartQuantityException;
use PHPUnit\Framework\TestCase;

use app\libraries\Cart;
use app\libraries\Product;

class CartTest extends TestCase
{
    public function test_if_cart_is_empty()
    {
        $cart = new Cart();

        $this->assertEmpty($cart->getCart());
    }

    public function test_if_cart_is_not_empty()
    {
        $cart = new Cart();

       $this->assertNotEmpty($cart->getCart());
    }

    public function test_catch_exception_if_cart_have_more_than_3_products()
    {
        $this->expectException(CartQuantityException::class);

        $product1 = new Product;
        $product2 = new Product;
        $product3 = new Product;

        $cart = new Cart;
        $cart->add($product1);
        $cart->add($product2);
        $cart->add($product3);

       // $this->assertNotEmpty($cart->getCart());

    }

    public function test_if_products_in_cart_is_greater_than_1()
    {
      $cart = new Cart;
      $cart->add(new Product);
      $cart->add(new Product);
      $this->assertGreaterThan(1, count($cart->getCart()));
    }
}


