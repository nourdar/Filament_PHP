<?php use Illuminate\Support\Str; ?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? $settings?->name }}</title>
    <meta name="description" content="{{ $settings?->description }}">
    <meta name="keywords" content="{{ $settings?->keywords }}">

    <meta property="og:title" content="{{ $title ?? $settings?->name }}" />
    @if (isset($product) && !empty($product))
        <meta property="og:description" content="{{ $product?->description }}" />
        <meta property="og:image" content="{{ asset('storage/' . $product?->image) }}" />
    @else
        <meta property="og:description" content="{{ $settings?->description }}" />
        <meta property="og:image" content="{{ asset('storage/' . $settings?->logo) }}" />
    @endif

    <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings?->logo) }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&family=Diphylleia&family=Work+Sans:wght@200&display=swap" rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    @stack('styles')

    @include('facebookpixel::head')


    {!! $settings?->head_code !!}
</head>


@if (isset($settings->style[0]))
    <style>
        html {
  scroll-behavior: smooth !important;
}

        .font-diph {
            font-family: 'Diphylleia'  !important;
        }

        .font-cairo {
            font-family: 'cairo'  !important;
        }

        .font-work-sans {
            font-family: 'work sans'  !important;
        }

        .bg-btn-primary {
            background-color: {{ $settings->style[0]['bg-btn-primary'] .' !important' ?? '' }}
        }

        select, input, form, textarea, .checkout-form{
            border-color : {{ $settings->style[0]['bg-btn-primary'] .' !important' ?? '' }}
        }

        .bg-btn-primary:hover {
            background-color: {{ $settings->style[0]['bg-btn-primary-hover'] .' !important'?? '' }}
        }

        .btn-primary-text-color {
            color: {{ $settings->style[0]['btn-primary-text-color'] .' !important'?? 'white' }}
        }

        .btn-primary-text-color:hover {
            color: {{ $settings->style[0]['btn-primary-text-color-hover'].' !important'?? 'white' }}
        }
    </style>
@endif



<body class="text-base leading-normal tracking-normal text-gray-600 bg-white font-cairo ">


    @include('facebookpixel::body')



    @include('shop.components.loader')

    @include('shop.components.nav')

    @include('shop.components.search')

    @include('shop.components.phone-call')
