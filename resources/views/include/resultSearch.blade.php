<table class="table table-hover">
  <tbody>
  @foreach($products as $product)
      <tr>
          <td scope="row">{{$loop->iteration}}</td>
          <td>{{$product->name}}</td>
          <td>{{$product->correctedPrice}}</td>
          <td>
              @foreach($product->coloring as $cod )
                    <div  style="width:20px; height: 20px; background-color: {{$cod}}; position: relative; float: left; margin-left: 10px;"></div>
              @endforeach
          </td>
          <td>{{$product->description}}</td>
      </tr>
  @endforeach
  </tbody>
</table>
{{ $products->links() }}

