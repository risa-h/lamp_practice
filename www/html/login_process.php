<?php
// Modelファイルの読み込み
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
// POSTメソッドで送られたtokenの値を取得
$token = get_post('token');
// トークンのチェック
if (is_valid_csrf_token($token) === false) {
  // 照会の結果、不正なアクセスである場合、ログイン画面へリダイレクト
  redirect_to(LOGIN_URL);
}
// トークンの破棄
unset($_SESSION["csrf_token"]);
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