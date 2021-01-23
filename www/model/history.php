<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// 指定ユーザーの購入履歴の取得
function get_orders($db, $user_id){
    $sql = "
      SELECT
        order_id,
        order_datetime,
        total_price
      FROM
        orders
      WHERE
        user_id = :user_id
      ORDER BY order_datetime DESC
    ";
    $array = array(':user_id' => $user_id);
    return fetch_all_query($db, $sql, $array);
}

// 注文番号から注文履歴を取得
function get_order_by_order_id($db, $order_id){
    $sql = "
        SELECT
            order_datetime,
            total_price,
            user_id
        FROM
            orders
        WHERE
            order_id = :order_id
    ";
    $array = array(':order_id' => $order_id);
    return fetch_query($db, $sql, $array);
}

// 注文明細の取得
function get_details($db, $order_id){
    $sql = "
        SELECT
            items.name,
            details.price,
            details.amount
        FROM
            details
        JOIN
            items
        ON
            details.item_id = items.item_id
        WHERE
            order_id = :order_id    
    ";
    $array = array(':order_id' => $order_id);
    return fetch_all_query($db, $sql, $array);
}

// 管理者の場合の購入履歴の取得
function get_admin_orders($db){
    $sql = "
      SELECT
        order_id,
        order_datetime,
        total_price
      FROM
        orders
      ORDER BY order_datetime DESC
    ";
    return fetch_all_query($db, $sql);
}

?>