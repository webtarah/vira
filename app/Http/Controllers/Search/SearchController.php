<?php

namespace App\Http\Controllers\Search;

use App\Color;
use App\Color_product;
use App\Http\Requests\SearchRequest;
use App\Product;
use Illuminate\Http\Request;

class SearchController extends ASearch
{

    public function search(SearchRequest $request)
    {
        $word = $request->word;

        $item=self::setWordForSearchInFullText($word);

        $products= Product::whereRaw("match(name,description) against (? in boolean mode)", [$item])
            ->distinct()
            ->with('getColor')
            ->get();

        $data=self::setPriceAndDiscount($products);

        //get minPrice and maxPrice
        $price=self::minAndMaxPrice($data['priceSortArray']);
        $minPrice=$price['min'];
        $maxPrice=$price['max'];

        // create $product=>final product that must show
        $products = Product::whereIn('id', $data['array_product_id'])
            ->with('getColor')
            ->paginate($this->numberProductShowInPage)
            ->withPath(url('search') . '?word=' . $word);

        //set price of product
        $products=self::setProductProperty($products,$data['price']);

        // create $colorsFilter for show in color filter
        $colorsFilter = self::makeColorFilter($data['array_product_id']);

        //set products color
        $array_color = self::makeProductColor($products);


        $data = [
            'products' => $products,
            'colorsFilter' => $colorsFilter,
            'word' => $word,
            'maxPrice' => $maxPrice,
            'minPrice' => $minPrice,
            'array_color' => $array_color,
            'ajaxUrl'    => 'searchFilter',
        ];

        return view('search', $data);
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {
            (isset($request->word)) ? $word = $request->word : $word = '';

            /*serach in product*/
            $filter_id = $request->get('filter_id');
            $array1 = explode(',', $filter_id);
            $colors = array();
            foreach ($array1 as $key => $value) {
                $e = explode('-', $value);
                if (array_key_exists('1', $e)) {
                    $colors[] = $e[1];
                }
            }

            $colors2 = Color::whereIN('name', $colors)->get();
            $colors_id = array();
            foreach ($colors2 as $color2) {
                $colors_id[] = $color2->id;
            }

            $color_pro = Color_product::whereIn('color_id', $colors_id)->pluck('product_id')->toArray();

            //set mimn and max price
            $minPrice = $request->minPrice;
            $maxPrice = $request->maxPrice;

            // get products that are eligible
            $item=self::setWordForSearchInFullText($word);

            $array_product_id= Product::whereRaw("match(name,description) against (? in boolean mode)", [$item])
                ->distinct()
                ->pluck('id')->all();


            if (!empty($color_pro)) {
                $array_product_id = array_intersect($array_product_id, $color_pro);
            }

            //set price and discount
            $data=self::setPriceAndDiscountWithId($array_product_id,$minPrice,$maxPrice);

            // create $product=>final product that must show
            $products = Product::whereIn('id', $data['array_product_id'])
                ->with('getColor')
                ->paginate($this->numberProductShowInPage)
                ->withPath('');

            //set price of product
            $products=self::setProductProperty($products,$data['price']);

            //set products color
            $array_color = self::makeProductColor($products);

            $data=[
                'products'  =>  $products,
                'array_color'  =>  $array_color,
            ];

            return view('include.resultSearch',$data);
        }
        return false;

    }
}
