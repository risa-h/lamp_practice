<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
// セッション開始
session_start();
// 既にログイン済である場合
if(is_logined() === true){
  // ホーム画面へリダイレクト
  redirect_to(HOME_URL);
}
// トークンの生成
$token = get_csrf_token();
// Viewファイルの読み込み
include_once VIEW_PATH . 'login_view.php';