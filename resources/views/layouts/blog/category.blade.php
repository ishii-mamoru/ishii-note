<div class="col-md-2 col-lg-2 col-xl-2">
  <p class="category-title"><b>カテゴリー</b></p>
  @foreach($categories as $category)
    <div class="padding-bottom-20px">
      <a href="{{ route('blog.category', ['categoryId' => $category->id]) }}">{{ $category->name }}</a>
    </div>
  @endforeach
</div>