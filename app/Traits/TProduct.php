<?php

namespace App\Traits;


trait TProduct
{

    /**
     * @param $products
     * @return array
     * make array include id of products
     * create $Price[product_id]
     * create $priceSortArray => array that include price of product for get minPrice and maxPrice
     */
    public function setPriceAndDiscount($products)
    {
        $array_product_id = $price = $priceSortArray = array();
        foreach ($products as $product) {
            foreach ($product->getColor as $color) {
                $array_product_id[] = $product->id;
                $price[$product->id][] = $color->pivot->price;
                $priceSortArray[] = $color->pivot->price;
            }
        }
        $array_product_id = array_unique($array_product_id);
        return [
            'array_product_id' => $array_product_id,
            'price' => $price,
            'priceSortArray' => $priceSortArray,
        ];
    }

    /**
     * @param $products
     * @return mixed
     * set or change product property.
     */
    public function setProductProperty($products)
    {
        $data = self::setPriceAndDiscount($products);
        $prices = $data['price'];


        foreach ($products as $product) {

            (isset($prices[$product->id]))?$price = $prices[$product->id]:$price=array();

            //set price with discount and price on one KG with discount
            $totalPrice = 0;
            foreach ($price as $p) {
                $totalPrice += $p;
            }

            if ($totalPrice == 0) {
                $product->correctedPrice = __('messages.withOutPrice');
            } else {
                sort($price);
                $price=array_filter($price,function ($val){ if($val != 0) return true; else return false; });

                if (reset($price) == end($price)) {
                    $product->correctedPrice = reset($price) . ' ' . __('messages.Currency');
                } else {
                    $product->correctedPrice =end($price) . " ~ " . reset($price) . ' ' . __('messages.Currency');
                }
            }


            //coloring
         //   if (isset($product->getColor)) {
                $color = array();
                foreach ($product->getColor as $value) {
                    $color[$value->id] = $value->hex_color;
                }
                $product->coloring = $color;
          /*  } else {
                $product->coloring = self::makeProductColor([$product->id])[$product->id];
            }*/

        }
        return $products;
    }


    /**
     * @param $products with getColor
     * @return array : key is product Id , value is product hex color code
     */
    public function makeProductColor($products)
    {
        $array_color = array();
        foreach ($products as $product) {
            $arr = array();
            foreach ($product->getColor as $color) {
                $arr[$color->id] = $color->hex_color;
            }
            $array_color[$product->id] = $arr;
        }
        return $array_color;
    }



}