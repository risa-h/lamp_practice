<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
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
// ログイン中のユーザーのカートにある商品情報の取得
$carts = get_user_carts($db, $user['user_id']);
// POSTメソッドで送られたtokenの値を取得
$token = get_post('token');

// トークンのチェック
if (is_valid_csrf_token($token) === false) {
  // 照会の結果、不正なアクセスである場合、ログイン画面へリダイレクト
  redirect_to(LOGIN_URL);
}
// トークンの破棄
unset($_SESSION["csrf_token"]);

// カートの商品の購入に失敗した場合
if(purchase_carts($db, $carts) === false){
  // セッションにエラーメッセージを追加
  set_error('商品が購入できませんでした。');
  // カート画面にリダイレクト
  redirect_to(CART_URL);
}
// 合計金額を出す
$total_price = sum_carts($carts);

// トランザクション開始
// $dbh->beginTransaction();
// 購入履歴テーブルに保存 $total_price,$user['user_id']を使ってINSERT文を実行する
// 履歴テーブルへの保存に失敗した時
// ロールバック
// カート画面へリダイレクト
// 履歴テーブルへの保存が成功した時
// $order_id = $dbh->lastInsertId();
// 購入明細テーブルに保存
// $carts, $order_id を渡してINSERT文を実行する
// 購入明細テーブルへの保存に失敗した時
// ロールバック処理、カート画面へリダイレクト
// 購入明細テーブルへの保存に成功した時
// コミット処理
// $dbh->commit();

// 履歴テーブル、明細テーブルへのデータ保存
// 保存に失敗した場合
if(insert_orders_details($db, $total_price, $user, $carts) === false){
  set_error('履歴へのデータ保存に失敗しました。');
  redirect_to(CART_URL);
}

// Viewファイルの読み込み
include_once '../view/finish_view.php';