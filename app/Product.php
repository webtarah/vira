<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps=false;
    protected $fillable=['name','description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * return colors of product
     */
    public function getColor($minPrice = '', $maxPrice = '')
    {
        $joinTable = 'color_product';
        if ($minPrice != '' && $maxPrice != '') {
            return $this->belongsToMany('App\Color', $joinTable, 'product_id', 'color_id')
                ->withPivot('price')
               // ->havingRaw("$joinTable.price  >= $minPrice ")
               // ->havingRaw("$joinTable.price  <= $maxPrice ");
                ->where("$joinTable.price",'>=', $minPrice )
                ->where("$joinTable.price",'<=', $maxPrice );
        } else {
            return $this->belongsToMany('App\Color', $joinTable, 'product_id', 'color_id')
                ->withPivot('price');
        }
    }


}
