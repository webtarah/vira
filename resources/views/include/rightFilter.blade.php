

    {{--price filter--}}
    <div class="product-filter-box">
        <div class="filter-box-title"><i  aria-hidden="true"></i><h5>@lang('messages.BasePrice')</h5></div>
        <div class="filter-box-container" >
            <input type="text" id="price-range" >
            {{--<input id="demo_0" type="text" name="" value="" class="irs-hidden-input" tabindex="-1" readonly="">--}}
        </div>
    </div>

    {{--color filter--}}
    @isset($colorsFilter)
        <div class="product-filter-box">
            <div class="filter-box-title"><i  aria-hidden="true"></i><h5>@lang('messages.BaseColor')</h5></div>
            <div class="filter-box-container">
                @foreach($colorsFilter as $color)
                    <div class="filter-box-item">
                        <span class="color-box" style="background: {{$color->hex_color}}"></span>
                        <span>{{$color->name}}</span>
                        <div class="filter-box-item-info" >
                            <label class="onoffbtn">
                                <input class="filter-product" value='2-{{$color->name}}' type="checkbox" onclick="setFilter()">
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endisset

