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
// ログインしているユーザーのカートの商品情報の取得
$carts = get_user_carts($db, $user['user_id']);
// カートの商品の合計金額を取得
$total_price = sum_carts($carts);
// トークンの生成
$token = get_csrf_token();
// Viewファイルの読み込み
include_once VIEW_PATH . 'cart_view.php';