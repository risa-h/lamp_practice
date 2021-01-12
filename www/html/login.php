<?php
// Modelファイルの取得
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
// セッション開始
session_start();
// セッションIDを取得した場合
if(is_logined() === true){
  // ホーム画面へリダイレクト
  redirect_to(HOME_URL);
}
// Viewファイルの取得
include_once VIEW_PATH . 'login_view.php';