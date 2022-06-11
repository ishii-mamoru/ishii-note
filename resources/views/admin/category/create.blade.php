@extends('layouts.admin.layout')
@section('category-create-active', 'active')
@section('card-header', '新規作成')
@section('card-body')
  <form method="POST" action="{{ route('admin.category.store') }}">
    @csrf
    <div class="row">
      <div class="form-group col-8">
        <label><b>カテゴリー名</b></label>
        <input class="form-control" type="text" name="name" placeholder="カテゴリー名を入力してください" value="{{ old('name') }}">
        @error('name')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
      <div class="form-group col-4">
        <label><b>順番</b></label>
        <input class="form-control" type="number" name="order" value="{{ old('order') }}">
        @error('order')
          <div class="error">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="text-align-center">
      <input class="btn btn-primary" type="submit" value="新規作成">
    </div>
  </form>
@endsection
@section('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.4/jquery.validate.min.js" integrity="sha512-FOhq9HThdn7ltbK8abmGn60A/EMtEzIzv1rvuh+DqzJtSGq8BRdEN0U+j0iKEIffiw/yEtVuladk6rsG4X6Uqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('/js/admin/category/form.js') }}"></script>
@endsection