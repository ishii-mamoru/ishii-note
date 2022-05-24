<div class="col-md-2 col-lg-2 col-xl-2">
  <p class="category-title"><b>カテゴリー</b></p>
  @foreach(config('consts.post.category') as $key => $value)
    <div class="padding-bottom-20px">
      <a href="{{ route('blog.category', ['categoryId' => $key]) }}">{{ $value }}</a>
    </div>
  @endforeach
</div>