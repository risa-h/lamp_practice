<?php

// データベースに接続する関数。戻り値 $dbh
function get_db_connect(){
  // MySQL用のDSN文字列
  $dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
 
  try {
    // データベースに接続
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    exit('接続できませんでした。理由：'.$e->getMessage() );
  }
  return $dbh;
}

// データベースから1行だけデータを取得する関数
function fetch_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    // 取得したデータ１行が戻り値。以降に記述される処理は実行されない。
    return $statement->fetch();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  // データの取得に失敗した場合。戻り値 false
  return false;
}

// データベースから全てのデータを取得する関数
function fetch_all_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    $statement->execute($params);
    // 取得したデータを配列で返す。
    return $statement->fetchAll();
  }catch(PDOException $e){
    set_error('データ取得に失敗しました。');
  }
  // データの取得に失敗した場合。戻り値 false
  return false;
}

// データベースの更新をする関数。
function execute_query($db, $sql, $params = array()){
  try{
    $statement = $db->prepare($sql);
    // SQLの実行が戻り値。
    return $statement->execute($params);
  }catch(PDOException $e){
    set_error('更新に失敗しました。');
  }
  // データベースの更新に失敗した場合。戻り値 false
  return false;
}