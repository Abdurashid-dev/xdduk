@extends('layout.frontend')
@section('style')
    <link rel="stylesheet" href="{{asset('frontend/css/jquery.fancybox.min.css')}}">
@stop
@section('content')
    <!--Content-->
    <div class="content">
        <div class="bg"></div>
        <div class="container">
            <div class="side-bar">
                <div class="side-bar__items">
                    @foreach($allNews as $item)
                        <div class="side-bar__items--item">
                            <h5 class="title">
                                <a href="{{route('new', $item->id)}}">{{$item->getValue('title')}}</a>
                            </h5>
                            <p class="date">13/12/2021</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="main-content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('index')}}">{{__('words.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('news')}}">{{__('words.news')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$new->getValue('title')}}</li>
                    </ol>
                </nav>
                <div class="options">
                    <div class="social">
                        @foreach($socials as $social)
                            <a href="{{$social->link}}" class="social__link"><i class="{{$social->icon}}"></i></a>
                        @endforeach
                    </div>
                    <button class="print-btn" onclick="window.print()">
                        <i class=" fas fa-print"></i>
                    </button>
                </div>
                <div class="main-content__top">
                    <h5 class="main-content__top--title">{{$new->getValue('title')}}</h5>
                    <p class="date">{{$new->created_at->format('d/m/Y')}}</p>
                </div>
                <div class="main-content__bottom mb-4">
                    <div class="main-content__bottom--carousel mt-3">
                        <div class="owl-carousel owl-theme">
                            <div class="item">
                                <a href="{{asset($new->image)}}" data-fancybox="group">
                                    <img src="{{asset($new->image)}}"
                                         alt="{{$new->getValue('title')}}"/>
                                </a>
                            </div>
                            @if($new->images)
                                @foreach($new->images as $image)
                                    <div class="item">
                                        <a href="{{asset($image->image)}}" data-fancybox="group">
                                            <img src="{{asset($image->image)}}"
                                                 alt="{{$new->getValue('title')}}"/>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="prev"></div>
                        <div class="next"></div>
                    </div>
                    {!! $new->getValue('content') !!}
                </div>
            </div>
        </div>
    </div>
    <!--Content end-->
@stop
@section('script')
    <script src="{{asset('frontend/js/jquery.fancybox.min.js')}}"></script>
@stop
