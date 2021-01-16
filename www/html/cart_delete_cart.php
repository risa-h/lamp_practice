<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
// セッション開始
session_start();
// 未ログインの場合、ログイン画面へリダイレクト
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
// POSTメソッドで送られてきたcart_idの値の取得
$cart_id = get_post('cart_id');
// データベースからカートの商品を削除した場合
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
} else {
  set_error('カートの削除に失敗しました。');
}
// カート画面へリダイレクト
redirect_to(CART_URL);