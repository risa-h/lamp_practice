<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
// セッション開始
session_start();
// 既にログイン済のユーザーの場合、ホーム画面へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// POSTメソッドで送られてきたname,password,password_confimation(確認用)の値を取得
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');
// データベース接続
$db = get_db_connect();

try{
  $result = regist_user($db, $name, $password, $password_confirmation);
  // ユーザー登録の条件を満たしていない場合
  if( $result=== false){
    // セッションにエラーメッセージを追加
    set_error('ユーザー登録に失敗しました。');
    // 新規登録ページへリダイレクト
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  // データベース接続でエラーが発生した場合
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}
// セッションに成功メッセージを追加
set_message('ユーザー登録が完了しました。');
// ログイン
login_as($db, $name, $password);
// ホーム画面へリダイレクト
redirect_to(HOME_URL);