<?php
// Modelファイル読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// セッション開始
session_start();
// ログインしていない場合、ログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベース接続
$db = get_db_connect();
// セッションに保存されているユーザーIDから、データベース内のユーザー情報を取得
$user = get_login_user($db);
// 管理者登録されていないユーザーIDの場合、ログインページにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// データベースにある全商品の情報を取得
$items = get_all_items($db);
// トークンの生成
$token = get_csrf_token();
// Viewファイル読み込み
include_once VIEW_PATH . '/admin_view.php';
