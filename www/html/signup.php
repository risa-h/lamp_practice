<?php
// Modelファイルの読み込み
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
// セッション開始
session_start();
// すでにログイン済の場合、ホーム画面へリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// トークンの生成
$token = get_csrf_token();
// Viewファイルの読み込み
include_once VIEW_PATH . 'signup_view.php';



