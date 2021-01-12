<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
// セッション開始
session_start();
// 未ログインのユーザーの場合、ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベース接続
$db = get_db_connect();
// ユーザ−IDを取得
$user = get_login_user($db);
// データベースから商品情報を取得
$items = get_open_items($db);
// Viewファイルを読み込み
include_once VIEW_PATH . 'index_view.php';