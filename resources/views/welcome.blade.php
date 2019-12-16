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
@endsection
