<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'user.php';
// セッション開始
session_start();
// 未ログインのユーザーの場合、ログインページへリダイレクト
if(is_logined() === false){
    redirect_to(LOGIN_URL);
}

// GETメソッドで送られてきたsortの値の取得
$sort = get_get('sort');
// データベース接続
$db = get_db_connect();
// ユーザ−IDを取得
$user = get_login_user($db);
// 商品情報の取得
$items = get_open_items_sort($db, $sort);
// トークンの生成
$token = get_csrf_token();

// 処理後ホーム
include_once VIEW_PATH . 'index_view.php';
?>