@extends('layout.frontend')
@section('content')
    <!--Content-->
    <div class="content">
        <div class="bg"></div>
        <div class="container">
            <div class="side-bar">
                <div class="side-bar__items">
                    @foreach($randomPages as $page)
                        <div class="side-bar__items--item">
                            <h5 class="title">
                                <a href="{{route('page',$page->slug)}}">{{$page->getValue('name')}}</a>
                            </h5>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="main-content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('index')}}">{{__('words.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('leaders')}}">{{__('words.leaders')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$leader->getValue('name')}}</li>
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
                    <h5 class="main-content__top--title">{{$leader->getValue('name')}}</h5>
                </div>
                <div class="main-content__bottom">
                    {!! $leader->getLeaderImage('width:100%; object-fit:contain') !!}
                    <br>
                    <div class="p-3">
                        {!!$leader->getValue('bio')!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Content end-->
@stop
