-- 購入履歴テーブルのカラム
-- 注文番号・購入日時・合計金額・ユーザーID
-- 主キーは注文番号
-- order_id order_datetime total_price user_id
CREATE TABLE orders (
    order_id INT(11) AUTO_INCREMENT,
    order_datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_price INT(11),
    user_id INT(11),
    PRIMARY KEY(order_id)
);
-- 購入明細テーブルのカラム
-- 購入明細ID・注文番号・商品ID・購入時の金額・購入数
-- 主キーは購入明細ID
-- detail_id order_id item_id price amount
CREATE TABLE details (
    detail_id INT(11) AUTO_INCREMENT,
    order_id INT(11),
    item_id INT(11),
    price INT(11),
    amount INT(11),
    PRIMARY KEY(detail_id)
);
