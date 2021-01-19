<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
  ";
  $array= array(':user_id' => $user_id);
  return fetch_all_query($db, $sql, $array);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";
  $array = array(':user_id' => $user_id, ':item_id' => $item_id);
  return fetch_query($db, $sql, $array);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";
  $array = array(':item_id' => $item_id, ':user_id' => $user_id, ':amount' => $amount);
  return execute_query($db, $sql, $array);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $array = array(':amount' => $amount, ':cart_id' => $cart_id);
  return execute_query($db, $sql, $array);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  $array = array(':cart_id' => $cart_id);
  return execute_query($db, $sql, $array);
}

// カート内商品の購入を実行する関数
function purchase_carts($db, $carts){
  // 購入可能か検証。検証の結果falseであれば、falseを返す
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  // 検証の結果、falseでなければ以下の処理が実行される
  foreach($carts as $cart){
    // 商品在庫テーブルのupdate
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }

  //　ユーザーのカートの中身を0にする
  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";
  $array = array(':user_id' => $user_id);
  execute_query($db, $sql, $array);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

// カート内商品が購入可能か検証する関数
function validate_cart_purchase($carts){
  // カート内に商品が入っていない場合、falseを返す
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

// 履歴テーブルと明細テーブルへのデータ保存
function insert_orders_details($db, $total_price, $user, $carts){
  // トランザクション開始
  $db->beginTransaction();
  // 購入履歴テーブルへのデータ保存
  if(insert_orders($db, $total_price, $user['user_id']) === false){
    set_error('購入履歴の保存に失敗しました');
    $db->rollback();
    return false;
  }
    // 購入履歴テーブルへのデータ保存に成功した場合、注文番号を取得
  $order_id = $db->lastInsertId();
    // 購入明細テーブルへのデータ保存
  if(insert_details($db, $order_id, $carts) === false){
    set_error('購入詳細の保存に失敗しました');
    $db->rollback();
    return false;
  }
  $db->commit();
  return true;
}

// 購入履歴テーブルへのデータ保存
function insert_orders($db, $total_price, $user_id){
  $sql = "
    INSERT INTO
      orders(
        total_price,
        user_id
      )
    VALUES(:total_price, :user_id)
  ";
  $array = array(':total_price' => $total_price, ':user_id' => $user_id);
  return execute_query($db, $sql, $array);
}

// 購入明細テーブルへのデータ保存
function insert_details($db, $order_id, $carts){
  foreach($carts as $cart){
    $sql = "
      INSERT INTO
        details(
        order_id,
        item_id,
        price,
        amount
        )
      VALUES(:order_id, :item_id, :price, :amount)
    ";
    $array = array(':order_id' => $order_id, ':item_id' => $cart['item_id'], ':price' => $cart['price'], ':amount' => $cart['amount']);
    if(execute_query($db, $sql, $array) === false){
      return false;
    }
  }
  return true;
}