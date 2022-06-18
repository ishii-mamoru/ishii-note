@extends('layouts.blog.layout')
@section('title', '技術メモ一覧')
@section('description', '技術メモの一覧です。')
@section('head')
  <link href="{{ asset('/css/blog/main.css') }}" rel="stylesheet">
@endsection
@section('content')
  <!-- Page Header-->
  <header class="masthead" style="background-image: url('/blog-template/images/home-bg.jpg')">
    <div class="container position-relative px-4 px-lg-5">
      <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">
          <div class="site-heading">
            <h2>メモ一覧</h2>
            <span class="subheading">技術メモの一覧です。</span>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Main Content-->
  <div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
      <div class="col-md-10 col-lg-8 col-xl-7">
        @foreach($posts as $post)
        <!-- Post preview-->
          <div class="post-preview">
            @if($post->category)
              @foreach ($post->category as $categoryId)
                <a href="{{ route('blog.category', ['categoryId' => $categoryId]) }}" class="category-tag" data-test="{{ 'category-tag-'.$categoryId }}">{{ $categories->find($categoryId)->name }}</a>
              @endforeach
            @endif
            <a href="{{ route('blog.show', ['postId' => $post->id]) }}">
              <h2 class="post-title">{{ $post->title }}</h2>
              <h3 class="post-subtitle">{{ $post->subtitle }}</h3>
            </a>
            <p class="post-meta">
              Posted on {{ date('Y.m.d H:i', strtotime($post->post_date)) }}
            </p>
          </div>
          <div class="d-flex justify-content-end mb-4"><a class="btn btn-primary text-uppercase" href="{{ route('blog.show', ['postId' => $post->id]) }}" data-test="{{ 'show-'.$post->id }}">もっと見る →</a></div>
          <!-- Divider-->
          <hr class="my-4" />
        @endforeach
      </div>
      @include('layouts.blog.category')
    </div>
  </div>
@endsection