<?php
// Modelファイル読み込み
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
// データベース接続
$db = get_db_connect();
// セッションに保存されているユーザーIDから、データベースに保存されているユーザー情報を取得
$user = get_login_user($db);
// 管理者でない場合、ログインページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// POSTメソッドで送られたitem_id、chenges_toの値を取得
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');
// changes_toの値がopenの場合
if($changes_to === 'open'){
  // データベースのステータスをupdate
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  // セッションにメッセージを保存
  set_message('ステータスを変更しました。');
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
}else {
  set_error('不正なリクエストです。');
}

// 管理者ページのトップページへリダイレクト
redirect_to(ADMIN_URL);