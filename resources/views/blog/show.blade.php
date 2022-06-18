@extends('layouts.blog.layout')
@section('title', $post->title)
@section('description', $post->subtitle)
@section('head')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.26.0/themes/prism-tomorrow.min.css" integrity="sha512-vswe+cgvic/XBoF1OcM/TeJ2FW0OofqAVdCZiEYkd6dwGXthvkSFWOoGGJgS2CW70VK5dQM5Oh+7ne47s74VTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="{{ asset('/css/blog/main.css') }}" rel="stylesheet">
  <link href="{{ asset('/css/blog/show.css') }}" rel="stylesheet">
@endsection
@section('content')
  <!-- Page Header-->
  <header class="masthead" style="background-image: url('/blog-template/images/post-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
      <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-9 col-xl-9">
          <div class="post-heading">
            <h1 class="title">{{ $post->title }}</h1>
            <h2 class="subheading subtitle">{{ $post->subtitle }}</h2>
            <span class="meta">
              Posted on {{ date('Y.m.d H:i', strtotime($post->post_date)) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Post Content-->
  <article class="mb-4">
    <div class="container px-4 px-lg-5">
      <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-9 col-xl-9">
          @if($post->category)
            @foreach ($post->category as $categoryId)
              <a href="{{ route('blog.category', ['categoryId' => $categoryId]) }}" class="category-tag" data-test="{{ 'category-tag-'.$categoryId }}">{{ $categories->find($categoryId)->name }}</a>
            @endforeach
          @endif
          {!! $post->content !!}
        </div>
        @include('layouts.blog.category')
      </div>
    </div>
  </article>
@endsection
@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.26.0/components/prism-core.min.js" integrity="sha512-NC2WFBzw/SdbWrzG0C+sg3iv1OITcQKsUitDcYKfOp9vxe92zpNlRc5Ad3q81kAp8Ff/fDV8pZQxdCCeyFdgLw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.26.0/plugins/autoloader/prism-autoloader.min.js" integrity="sha512-GP4x8UWxWyh4BMbyJGOGneiTbkrWEF5izsVJByzVLodP8CuJH/n936+yQDMJJrOPUHLgyPbLiGw2rXmdvGdXHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection