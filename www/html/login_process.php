<?php
// Modelファイルの取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
// セッション開始
session_start();
// ログイン済みの場合、ホーム画面へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// POSTメソッドで送られたname,passwordの値を取得
$name = get_post('name');
$password = get_post('password');
// データベースに接続
$db = get_db_connect();

// データベースからユーザーを特定
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}
// セッションにメッセージを追加
set_message('ログインしました。');
// 管理者の場合、管理者のページへリダイレクト
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
// ホーム画面へリダイレクト
redirect_to(HOME_URL);