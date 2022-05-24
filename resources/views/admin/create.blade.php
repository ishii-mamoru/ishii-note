@extends('layouts.admin.layout')
@section('create-active', 'active')
@section('card-header', '新規作成')
@section('card-body')
  <form method="POST" action="{{ route('admin.store') }}">
    @csrf
    <div class="row">
      <div class="form-group col-8">
        <label><b>タイトル</b></label>
        <input class="form-control" type="text" name="title" placeholder="タイトルを入力してください">
      </div>
      <div class="form-group col-2">
        <label><b>ステータス</b></label>
        <select class="form-control" name="status">
          @foreach(config('consts.post.status_label') as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group col-2">
        <label><b>投稿日時</b></label>
        <input class="form-control" type="datetime-local" name="post_date" value="{{ date('Y-m-d\TH:i') }}">
      </div>
    </div>
    <div class="form-group">
      <label><b>カテゴリー</b></label>
      <div class="row">
        @foreach(config('consts.post.category') as $key => $value)
          <div class="form-check col-2 padding-left-40px">
            <input class="form-check-input" type="checkbox" name="category[]" id="category{{ $key }}" value="{{ $key }}">
            <label class="form-check-label" for="category{{ $key }}">{{ $value }}</label>
          </div>
        @endforeach
      </div>
    </div>
    <div class="form-group">
      <label><b>サブタイトル</b></label>
      <input class="form-control" type="text" name="subtitle" placeholder="サブタイトルを入力してください">
    </div>
    <div class="form-group" id="content-area">
      <label><b>本文</b></label>
      <textarea class="form-control" name="content" rows="20" placeholder="本文を入力して下さい"></textarea>
    </div>
    <div class="text-align-center">
      <input class="btn btn-primary" type="submit" value="新規作成">
    </div>
  </form>
@endsection
@section('js')
  <script src="{{ asset('/js/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="https://cdn.tiny.cloud/1/xq0npliqrddx8b62rs4uzshn2wapew3cvge90e388lr78gxb/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script src="{{ asset('/js/blog/form.js') }}"></script>
@endsection