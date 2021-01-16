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
// セッションに保存されているユーザーIDから、データベースに保存されているユーザー情報を取得
$user = get_login_user($db);
// 管理者でない場合、ログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// POSTメソッドで送られたtokenの値を取得
$token = get_post('token');
// トークンのチェック
if (is_valid_csrf_token($token) === false) {
  // 照会の結果、不正なアクセスである場合、ログイン画面へリダイレクト
  redirect_to(LOGIN_URL);
}
// トークンの破棄
unset($_SESSION["csrf_token"]);
// POSTメソッドで送信されたitem_id,stockの値を取得する
$item_id = get_post('item_id');
$stock = get_post('stock');
// データベースのストックの値を変更した場合
if(update_item_stock($db, $item_id, $stock)){
  // セッションにメッセージを追加
  set_message('在庫数を変更しました。');
} else {
  set_error('在庫数の変更に失敗しました。');
}
// 管理者ページのトップ画面へリダイレクト
redirect_to(ADMIN_URL);