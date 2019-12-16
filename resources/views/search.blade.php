@extends('layouts.app')
@section('header')
    <link media="all" type="text/css" rel="stylesheet" href="{{asset('css/ion.rangeSlider.min.css')}}">
    <link media="all" type="text/css" rel="stylesheet" href="{{asset('css/temp.css')}}">
@endsection
@section('content')
    <div class="container">
        {{-- search box --}}
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <form method="GET" action="{{ route('search') }}">
                            {{-- input search word --}}
                            <div class="form-group row">
                                <div class="col-md-2 offset-md-1"><button type="submit" class="btn btn-primary">search</button></div>
                                <div class="col-md-8">
                                    <input id="word" type="input" class="form-control @error('word') is-invalid @enderror" name="word" value="{{ old('word') }}"  autofocus>
                                    @error('word')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="form-group row ">

        <div class="col-md-8">
            {{--loading--}}
            <div id="loading" class="row justify-content-center " style="display: none">
                <div><img class="w-100" src="{{asset('images/loading.gif')}}"> </div>
            </div>
            {{--results --}}
            <div class="card">
                <div class="card-header">results</div>
                <div class="card-body">
                    @if($products->isEmpty())
                        <div class="card-body">
                            <div class="alert alert-success" role="alert">There are no result.</div>
                        </div>
                     @endif
                    <div id="productsAjax">@include('include.resultSearch')</div>
                </div>
            </div>
        </div>

        {{-- filters --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">filter</div>
                <div class="card-body">
                    @include('include.rightFilter')
                </div>
            </div>
        </div>

        {{--sort filter--}}
        <div class="extra-controls">
            <input type="hidden" class="js-input-from"  value="{{$minPrice}}" />
            <input type="hidden" class="js-input-to" value="{{$maxPrice}}" />
            <input type='hidden' class="word" value="{{$word}}">
        </div>

    </div>
@endsection
@section('footer')
    <script type="text/javascript" src="{{asset('js/ion.rangeSlider.min.js')}}"></script>
    <script type="text/javascript">

        //on or off filter
        (function (document) {
            $('.onoffbtn').on('click', function(){
                if($(this).children().is(':checked')){
                    $(this).addClass('active');
                }
                else{
                    $(this).removeClass('active');
                }
            });

        })(document);


        var $range = $("#price-range"),
            $inputFrom = $(".js-input-from"),
            $inputTo = $(".js-input-to"),
            instance,
            min = {{$minPrice}},
            max = {{$maxPrice}};

        $range.ionRangeSlider({
            type: "double",
            min: min,
            max: max,
            onFinish: updateInputs
        });
        instance = $range.data("ionRangeSlider");

        function updateInputs (data) {
            from = data.from;
            to = data.to;
            $inputFrom.prop("value", from);
            $inputTo.prop("value", to);
            setFilter();
        }

        function setFilter(){
            $('#loading').css('display','block');
            minPrice=$(".js-input-from").val();
            maxPrice=$(".js-input-to").val();
            word=$(".word").val();
            var array=new Array();
            var j=0;
            var checkbox=document.getElementsByClassName('filter-product');
            for(var i=0;i<checkbox.length;i++){
                if(checkbox[i].checked){
                    array[j]=checkbox[i].value;
                    j++;
                }
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }});

            $.ajax({
                url:'{{route($ajaxUrl)}}',
                type:"POST",
                data:'filter_id='+array+'&word='+word+'&minPrice='+minPrice+'&maxPrice='+maxPrice,
                success:function(data)
                {
                    $('#loading').css('display','none');
                    $("#productsAjax").html(data);
                }
            });
        }


    </script>

@endsection