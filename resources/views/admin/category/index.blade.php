@extends('layouts.admin.layout')
@section('category-index-active', 'active')
@section('card-header', 'カテゴリー一覧')
@section('card-body')
  <div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>順序</th>
          <th>カテゴリー</th>
          <th>編集</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->order }}</td>
            <td>{{ $category->name }}</td>
            <td class="text-align-center"><a href="{{ route('admin.category.edit', ['categoryId' => $category->id]) }}" class="btn btn-sm btn-primary">編集</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection