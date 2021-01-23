<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'history.php';

// セッション開始
session_start();
// 未ログインの場合、ログインページへリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}
// データベースに接続
$db = get_db_connect();
// ユーザーIDの取得
$user = get_login_user($db);
// POSTメソッドで送られてきたorder_idの値の取得
$order_id = get_post('order_id');
// POSTメソッドで送られたtokenの値を取得
$token = get_post('token');

// トークンのチェック
if (is_valid_csrf_token($token) === false) {
    // 照会の結果、不正なアクセスである場合、ログイン画面へリダイレクト
    redirect_to(LOGIN_URL);
}
// トークンの破棄
unset($_SESSION["csrf_token"]);

// order_idの値から、ordersテーブルの値を取得
$order = get_order_by_order_id($db, $order_id);
// 注文番号に対応するuser_idがログイン中のユーザーのIDを一致、もしくはログイン中のユーザーのtypeが1(管理者)の場合
if($order['user_id'] !== $user['user_id'] && $user['type'] !== USER_TYPE_ADMIN){
   // ログインページへリダイレクト
   redirect_to(LOGIN_URL);
}

// detailsテーブルから情報を取得
$details = get_details($db, $order_id);
// Viewファイルの読み込み
include_once '../view/detail_view.php';
?>