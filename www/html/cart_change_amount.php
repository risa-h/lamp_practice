<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
// セッション開始
session_start();
// 未ログインのユーザーの場合、ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベースへ接続
$db = get_db_connect();
// ユーザーIDの取得
$user = get_login_user($db);
// POSTメソッドで送られたtokenの値を取得
$token = get_post('token');
// トークンのチェック
if (is_valid_csrf_token($token) === false) {
  // 照会の結果、不正なアクセスである場合、ログイン画面へリダイレクト
  redirect_to(LOGIN_URL);
}
// トークンの破棄
unset($_SESSION["csrf_token"]);
// POSTメソッドで送られてきたcart_id,amountの値を取得
$cart_id = get_post('cart_id');
$amount = get_post('amount');
//　カートの在庫数の変更が出来た場合、
if(update_cart_amount($db, $cart_id, $amount)){
// セッションに成功メッセージを追加する
  set_message('購入数を更新しました。');
} else {
// セッションにエラーメッセージを追加する
  set_error('購入数の更新に失敗しました。');
}
// カート画面へリダイレクト
redirect_to(CART_URL);