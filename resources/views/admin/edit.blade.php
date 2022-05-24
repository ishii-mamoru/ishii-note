@extends('layouts.admin.layout')
@section('card-header', '編集')
@section('card-body')
  <form method="POST" action="{{ route('admin.update', ['postId' => $post->id]) }}">
    @csrf
    <div class="row">
      <div class="form-group col-8">
        <label><b>タイトル</b></label>
        <input class="form-control" type="text" name="title" value="{{ $post->title }}" placeholder="タイトルを入力してください">
      </div>
      <div class="form-group col-2">
        <label><b>ステータス</b></label>
        <select class="form-control" name="status">
          @foreach(config('consts.post.status_label') as $key => $value)
            <option value="{{ $key }}" @if($key == $post->status) selected @endif>{{ $value }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group col-2">
        <label><b>投稿日時</b></label>
        <input class="form-control" type="datetime-local" name="post_date" value="{{ date('Y-m-d\TH:i', strtotime($post->post_date)) }}">
      </div>
    </div>
    <div class="form-group">
      <label><b>カテゴリー</b></label>
      <div class="row">
        @foreach(config('consts.post.category') as $key => $value)
          <div class="form-check col-2 padding-left-40px">
            <input class="form-check-input" type="checkbox" name="category[]" id="category{{ $key }}" value="{{ $key }}" @if(in_array($key, $post->category)) checked @endif>
            <label class="form-check-label" for="category{{ $key }}">{{ $value }}</label>
          </div>
        @endforeach
      </div>
    </div>
    <div class="form-group">
      <label><b>サブタイトル</b></label>
      <input class="form-control" type="text" name="subtitle" value="{{ $post->subtitle }}" placeholder="サブタイトルを入力してください">
    </div>
    <div class="form-group" id="content-area">
      <label><b>本文</b></label>
      <textarea class="form-control" name="content" rows="20" placeholder="本文を入力して下さい">{{ $post->content }}</textarea>
    </div>
    <div class="text-align-center">
      <a href="{{ route('blog.show', ['postId' => $post->id]) }}" class="btn btn-success" target="_blank">表示</a>
      <input class="btn btn-primary" type="submit" value="更新">
      <input class="btn btn-danger" type="submit" value="削除" formaction="{{ route('admin.destroy', ['postId' => $post->id]) }}" id="delete">
    </div>
  </form>
@endsection
@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.4/jquery.validate.min.js" integrity="sha512-FOhq9HThdn7ltbK8abmGn60A/EMtEzIzv1rvuh+DqzJtSGq8BRdEN0U+j0iKEIffiw/yEtVuladk6rsG4X6Uqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.tiny.cloud/1/xq0npliqrddx8b62rs4uzshn2wapew3cvge90e388lr78gxb/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script src="{{ asset('/js/admin/form.js') }}"></script>
@endsection