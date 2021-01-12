<?php
// Modelファイルを取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// セッション開始
session_start();
// ログインしていない場合、ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベースに接続
$db = get_db_connect();
// セッションに保存されているユーザーIDから、データベース内のユーザー情報を取得
$user = get_login_user($db);
// 管理者でない場合、ログインページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// POSTメソッドで送られてきたitem_idの値を取得
$item_id = get_post('item_id');

// データベースから商品を削除した場合
if(destroy_item($db, $item_id) === true){
  // セッションにメッセージを追加
  set_message('商品を削除しました。');
} else {
  set_error('商品削除に失敗しました。');
}


// 管理者ページのトップへリダイレクト
redirect_to(ADMIN_URL);