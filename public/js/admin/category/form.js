// バリデーション
var rules = {
  "name": {
    required: true,
  },
  "order": {
    required: true,
  },
};

var messages = {
  "name": {
    required: "※ カテゴリー名を入力して下さい",
  },
  "order": {
    required: "※ 順序を入力して下さい",
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
  if(!confirm('カテゴリーを削除しますか？')){
    return false;
  }
});