@extends('layouts.admin.layout')
@section('create-active', 'active')
@section('card-header', '新規作成')
@section('card-body')
  <form method="POST" action="{{ route('admin.store') }}">
    @csrf
    <div class="row">
      <div class="form-group col-8">
        <label><b>タイトル</b></label>
        <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="タイトルを入力してください">
        @error('title')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group col-2">
        <label><b>ステータス</b></label>
        <select class="form-control" name="status">
          @foreach(config('consts.post.status_label') as $key => $value)
            <option value="{{ $key }}" @if($key == old('status')) selected @endif>{{ $value }}</option>
          @endforeach
        </select>
        @error('status')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group col-2">
        <label><b>投稿日時</b></label>
        <input class="form-control" type="datetime-local" name="post_date" value="{{ date('Y-m-d\TH:i') }}">
        @error('post_date')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-group">
      <label><b>カテゴリー</b></label>
      <div class="row">
        @foreach($categories as $category)
          <div class="form-check col-2 padding-left-40px">
            <label class="form-check-label" for="category{{ $category->id }}">
              <input class="form-check-input" type="checkbox" name="category[]" id="category{{ $category->id }}" value="{{ $category->id }}">{{ $category->name }}
            </label>
          </div>
        @endforeach
        @error('category')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-group">
      <label><b>サブタイトル</b></label>
      <input class="form-control" type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="サブタイトルを入力してください">
      @error('subtitle')
        <div class="error">{{ $message }}</div>
      @enderror
    </div>
    <div class="form-group" id="content-area">
      <label><b>本文</b></label>
      <textarea class="form-control" name="content" rows="20" placeholder="本文を入力して下さい">{{ old('content') }}</textarea>
    </div>
    <div class="text-align-center">
      <input class="btn btn-primary" type="submit" value="新規作成">
    </div>
  </form>
@endsection
@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.4/jquery.validate.min.js" integrity="sha512-FOhq9HThdn7ltbK8abmGn60A/EMtEzIzv1rvuh+DqzJtSGq8BRdEN0U+j0iKEIffiw/yEtVuladk6rsG4X6Uqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.tiny.cloud/1/xq0npliqrddx8b62rs4uzshn2wapew3cvge90e388lr78gxb/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script src="{{ asset('/js/admin/form.js') }}"></script>
@endsection