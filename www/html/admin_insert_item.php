<?php
// Modelファイルを取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
//　セッション開始
session_start();
// ログインしていない場合、ログインページへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベースに接続
$db = get_db_connect();
// セッションに保存されているユーザーIDから、データベース内のユーザー情報を取得
$user = get_login_user($db);
// 管理者でない場合、ログインページへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// POSTメソッドで送られてきたname,price,status,stockの値を取得
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
// FILEメソッドで送られてきた値を取得
$image = get_file('image');
// 商品をデータベースに追加登録した場合
if(regist_item($db, $name, $price, $stock, $status, $image)){
  // セッションにメッセージを追加
  set_message('商品を登録しました。');
}else {
  set_error('商品の登録に失敗しました。');
}


redirect_to(ADMIN_URL);