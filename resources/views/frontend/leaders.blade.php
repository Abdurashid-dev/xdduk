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
                    <li class="breadcrumb-item active" aria-current="page">{{__('words.leaders')}}</li>
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
                <h5 class="main-content__top--title">{{__('words.leaders')}}</h5>
            </div>
            <div class="main-content__bottom">
                <div class="admins">
                    @foreach ($leaders as $leader)
                    <div class="admin">
                        <h5 class="admin__title">{{$leader->getValue('position')}}</h5>
                        <div class="admin__info">
                            <div class="admin__info--img">
                                <img src="{{asset($leader->image)}}" alt="{{$leader->getValue('name')}}" style="object-fit: contain" class="admin__info--img">
                            </div>
                            <div class="admin__info--text">
                                <h5 class="title">{{$leader->getValue('name')}}</h5>
                                <div class="details">
                                    <p>
                                        <i class="fas fa-phone-square-alt"></i>
                                        {{__('words.phone')}}: <a href="tel:{{$leader->number}}">{{$leader->number}}</a>
                                    </p>
                                    <p>
                                        <i class="fas fa-envelope-open-text"></i>
                                        {{__('words.email')}}: <a href="mailto:{{$leader->email}}">{{$leader->email}}</a>
                                    </p>
                                    <p>
                                        <i class="far fa-clock"></i>
                                        {{$leader->getValue('time')}}
                                    </p>

                                </div>
                                <a href="{{route('leader', $leader->id)}}">{{__('words.bio')}}</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!--Content end-->
@stop
