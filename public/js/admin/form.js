// バリデーション
var rules = {
  "title": {
    required: true,
  },
  "post_date": {
    required: true,
  },
  "subtitle": {
    required: true,
  },
};

var messages = {
  "title": {
    required: "※ タイトルを入力して下さい",
  },
  "post_date": {
    required: "※ 投稿日時を設定して下さい",
  },
  "subtitle": {
    required: "※ サブタイトルを入力して下さい",
  },
};

$("form").validate({
  rules: rules,
  messages: messages,
  errorPlacement: function(error, element) {
    error.insertAfter(element);
  },
});

// 削除ボタン 確認ダイアログ
$('#delete').click(function() {
  if(!confirm('投稿記事を削除しますか？')){
    return false;
  }
});

// TinyMCE
tinymce.init({ 
  selector:"textarea",
  language: "ja",
  branding: false,
  elementpath: false,
  plugins: 'link codesample table',
  menubar: 'format table',
  toolbar: 'undo redo | styleselect | link | codesample | table tabledelete',
  default_link_target: '_blank',
});