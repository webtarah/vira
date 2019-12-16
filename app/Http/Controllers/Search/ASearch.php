<?php
namespace App\Http\Controllers\Search;
//use App\Traits\TProduct;
use App\Color;
use App\Color_product;
use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use App\Product;


abstract class ASearch
{
    use \App\Traits\TProduct;

    protected $numberProductShowInPage=50;

    abstract public function search(SearchRequest $request);
    abstract public function filter(Request $request);

    public function setWordForSearchInFullText($sentence){
        $words = explode(' ', $sentence);
        $wordWithSpam=array();

        $item=$sentence.' ';
        foreach ($words as $w){
            $item.='*'.$w.'* ';
            $wordWithSpam[]="<span>".$w."</span>";
        }
        $item = "'".$item."'";

        return  $item;
    }

    //set min and max price
    public function minAndMaxPrice($priceArray){
        sort($priceArray);
        if(count($priceArray) == 0) $priceArray=[0];
        return [
            'min' => reset($priceArray),
            'max' => end($priceArray)
        ];
    }

    /**
     * @author  anari
     * @param $productIds
     * @return objects :
     * search between products and return colors that thay have for show in color filter
     */
    public function makeColorFilter($productIds)
    {
        $joins = Color_product::whereIn('product_id', $productIds)->get();
        $arr = array();
        foreach ($joins as $join) {
            $arr[] = $join->color_id;
        }
        $colorsFilter = Color::whereIn('id', $arr)->get();
        return $colorsFilter;
    }

    public  function  setPrice($products,$minPrice,$maxPrice){
        $array_product_id =$price=$priceSortArray=array();
        foreach ($products as $productId) {
            $product=Product::find($productId);
            foreach ($product->getColor($minPrice, $maxPrice)->get() as $color) {
                $array_product_id[]=$productId;
                $price[$productId][] =$color->pivot;
                $priceSortArray[]= $color->pivot->price;
            }
        }
        $array_product_id=array_unique($array_product_id);
        return [
            'array_product_id' => $array_product_id,
            'price'=>$price,
            'priceSortArray'=>$priceSortArray,
        ];
    }


}