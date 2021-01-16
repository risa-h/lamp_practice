<!-- セッションに保存されているエラーメッセージの表示 -->
<?php foreach(get_errors() as $error){ ?>
  <p class="alert alert-danger"><span><?php print h($error); ?></span></p>
<?php } ?>
<!-- セッションに保存されているメッセージの表示 -->
<?php foreach(get_messages() as $message){ ?>
  <p class="alert alert-success"><span><?php print h($message); ?></span></p>
<?php } ?>