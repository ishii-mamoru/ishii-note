@extends('layouts.admin.layout')
@section('index-active', 'active')
@section('card-header', '記事一覧')
@section('card-body')
  <div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>タイトル</th>
          <th>ステータス</th>
          <th>投稿日時</th>
          <th>更新日時</th>
          <th>表示</th>
          <th>編集</th>
        </tr>
      </thead>
      <tbody>
        @foreach($posts as $post)
          <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->title }}</td>
            <td>{{ config('consts.post.status_label')[$post->status] }}</td>
            <td>{{ date('Y.m.d H:i', strtotime($post->post_date)) }}</td>
            <td>{{ date('Y.m.d H:i', strtotime($post->updated_at)) }}</td>
            <td class="text-align-center"><a href="{{ route('blog.show', ['postId' => $post->id]) }}" class="btn btn-sm btn-success" target="_blank">表示</a></td>
            <td class="text-align-center"><a href="{{ route('admin.edit', ['postId' => $post->id]) }}" class="btn btn-sm btn-primary">編集</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection