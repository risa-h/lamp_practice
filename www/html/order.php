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
// データベースからユーザーIDを取得
$user = get_login_user($db);

// ログイン中のユーザーの購入履歴を取得
$orders = get_orders($db, $user['user_id']);
// 管理者の場合
if(is_admin($user) === TRUE){
    $orders = get_admin_orders($db);
}
// トークンの生成
$token = get_csrf_token();

// Viewファイルの読み込み
include_once '../view/order_view.php';
?>