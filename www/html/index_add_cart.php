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
// データベースへ接続
$db = get_db_connect();
// ユーザーIDの取得
$user = get_login_user($db);

// POSTメソッドで送られてきたitem_idの値を取得
$item_id = get_post('item_id');
// カートに商品を追加できた場合
if(add_cart($db,$user['user_id'], $item_id)){
  // セッションに成功メッセージを追加
  set_message('カートに商品を追加しました。');
} else {
// カートに商品を追加出来なかった場合
// セッションにエラーメッセージを追加
  set_error('カートの更新に失敗しました。');
}
// ホーム画面へリダイレクト
redirect_to(HOME_URL);