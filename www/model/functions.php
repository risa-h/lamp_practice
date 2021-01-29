<?php

// 変数のデータ型と値を調べる関数
function dd($var){
  var_dump($var);
  exit();
}

// 別ページへリダイレクトする関数
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

// GETで送られてきた値を取得する関数
function get_get($name){
  // $nameの値がセットされていた場合、その値が返り値
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  // $nameの値がセットされていないNULLの場合、空を返す
  return '';
}

// POSTで送られてきた値を取得する関数
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

// アップロードされたファイルの値を取得する関数
function get_file($name){
  // $nameの値がセットされている場合、その値を返す
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  // セットされていない場合、空配列を返す
  return array();
}

// セッションに登録されている値を取得する関数
function get_session($name){
  // セッションに値が登録されている場合、その値を返す
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  // 登録がない場合、空を返す
  return '';
}

// セッション変数に登録をする関数
function set_session($name, $value){
  // セッションにキーは$name、値は$valueで登録をする
  $_SESSION[$name] = $value;
}

// エラーをセッションに登録する関数
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

// セッションに保存されているエラーを取得する関数
function get_errors(){
  // セッションに保存されてるエラーの値を変数$errorsに代入
  $errors = get_session('__errors');
  // セッションに登録がなく、空であった場合
  if($errors === ''){
    // 空配列を返す
    return array();
  }
  //
  set_session('__errors',  array());
  // 取得したエラーの値を返す
  return $errors;
}

// セッションに登録されたエラーがあるかを確認する関数
function has_error(){
  // isset() null以外であればtrue nullであればfalseを返す。
  // count() !== 1  変数に含まれる要素の数を数える。0でない時はtrue 0の時、空配列の時はfalseを返す
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

// セッションにメッセージの値を登録する関数
function set_message($message){
  // セッションに__messagesがキー、$messageが値で登録する
  $_SESSION['__messages'][] = $message;
}

// セッションに登録されているメッセージを取得する関数
function get_messages(){
  // セッションに保存されている__messagesの値を取得し、変数$messagesに代入。
  $messages = get_session('__messages');
  // 値が空の場合、空配列を返す
  if($messages === ''){
    return array();
  }
  // 
  set_session('__messages',  array());
  // $messagesを返す
  return $messages;
}

// ログイン済か否かをチェックする関数
function is_logined(){
  // セッションに保存されているuser_idの値を取得
  // 空ではない、登録がある場合true 空である、登録がない場合はfalseを返す
  return get_session('user_id') !== '';
}

function get_upload_filename($file){
  // $fileが適切なファイルかチェック、falseが返ってきた場合
  if(is_valid_upload_image($file) === false){
    // 空を返す
    return '';
  }
  // exif_imagetype() ファイルの先頭バイトを読み、そのサインを調べる。
  // 正しいサインの場合、適切な定数。それ以外はfalseを返す。
  // 変数$mimetypeに代入
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

// 
function get_random_string($length = 20){
  // substr() 文字列の一部分を返す
  // 0から$lengthバイト文の文字列を返す
    // base_convert() 数値の基数を任意に変換する
    // ハッシュ化した値を基数$lengthで表した文字列を返す
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

// ファイルを指定した場所に保存する関数
function save_image($image, $filename){
  // アップロードしたファイルを移動する
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

// ファイルを削除する関数
function delete_image($filename){
  // ファイル、ディレクトリが存在する場合
  if(file_exists(IMAGE_DIR . $filename) === true){
    // ファイルを削除する
    unlink(IMAGE_DIR . $filename);
  // trueが返り値
    return true;
  }
  // ファイルが存在しなければfalseを返す
  return false;
  
}



// 文字列が規定の長さか否かチェックする関数
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  // 文字列の長さを取得する
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

// 半角英数字か否かをチェックする関数
function is_alphanumeric($string){
  // 半角英数字であればtrue そうでなければfalseを返す
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

// 正の整数か否かをチェックする関数
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

// 正規表現によるバリデーション、チェックをする関数
function is_valid_format($string, $format){
  // 正規表現によるマッチングを行い、マッチする場合trueを返す
  return preg_match($format, $string) === 1;
}


// アップロードされたファイルが有効か否かチェックする関数
function is_valid_upload_image($image){
  // $image['tmp_name]という名前のファイルがPOSTメソッドでアップロードされていたらtrue
  // アップロードされていない場合
  if(is_uploaded_file($image['tmp_name']) === false){
    // エラーメッセージを登録
    set_error('ファイル形式が不正です。');
    // falseが戻り値
    return false;
  }
  // exif_imagetype() ファイルの先頭バイトを読み、そのサインを調べる。
  // 正しいサインの場合、適切な定数。それ以外はfalseを返す。
  // 変数$mimetypeに代入
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  // 適切なファイルがアップロードされている場合、trueを返す
  return true;
}

// エスケープ処理
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// トークンの生成
function get_csrf_token(){
  // get_random_string()はユーザー定義関数。
  $token = get_random_string(30);
  // set_session()はユーザー定義関数。
  set_session('csrf_token', $token);
  return $token;
}

// トークンのチェック
function is_valid_csrf_token($token){
  if($token === '') {
    return false;
  }
  // get_session()はユーザー定義関数
  return $token === get_session('csrf_token');
}