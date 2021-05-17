<?php
// var_dump($_POST);
// メッセージを保存するファイルのパス設定
define( 'FILENAME', './filmdata.txt');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化（予期せぬエラーを防止）
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$review = array();
$review_array = array();
$success_message = null;
$error_message = array();

// $_POSTが空じゃなければ、trueを返す
if( !empty($_POST['btn_submit']) ){
  var_dump($_POST);

  // タイトルの入力チェック
	if( empty($_POST['title']) ) {
		$error_message[] = '映画タイトルを入力してください。';
	}

  // レビュー内容の入力チェック
	if( empty($_POST['content']) ) {
		$error_message[] = 'レビュー内容を入力してください。';
	}

  // 未入力項目がない場合、ファイル書き込み
  if( empty($error_message) ) {
    if( $file_handle = fopen( FILENAME, "a") ) {
      // 書き込み日時を取得
      $now_date = date("Y-m-d H:i:s");

      // 書き込むデータを作成
      $data = "'".$_POST['title']."','".$_POST['content']."','".$now_date."'\n";

      // 書き込み
      fwrite( $file_handle, $data);

      // ファイルを閉じる
      fclose( $file_handle);

      $success_message = 'レビューを投稿しました。';
    }
  }
}

// ファイルの読み込み
if( $file_handle = fopen( FILENAME,'r') ) {
  while( $data = fgets($file_handle) ){

    // preg_sprit,文字列を特定の文字で分割する関数
    $split_data = preg_split( '/\'/', $data);

    $review = array(
        'title' => $split_data[1],
        'content' => $split_data[3],
        'post_date' => $split_data[5]
    );
    array_unshift( $review_array, $review);
    // echo $data . "<br>";
  }

  // ファイルを閉じる
  fclose( $file_handle);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">
  <title>Filmhouse</title>
  <style>

  </style>
</head>
<body>
  <!-- ヘッダー部分 -->
  <header>
    <div class="container page-header">
      <nav class="nav-left">
        <h1 class="page-title"><a href="#">Filmhouse</a></h1>
      </nav>
      <nav class="nav-right">
        <ul class="main-nav">
          <li class="nav-link">
            <button type="button" class="btn btn-primary nav-btn">レビュー</button>
          </li>
          <li class="nav-link">
            <button type="button" class="btn btn-primary nav-btn">ログイン</button>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <main>
    <div class="wrapper main-wrapper">
      <!-- 新規レビュー -->
      <div class="new-post-box">
        <form method="post">
          <div>
            <label for="title">映画タイトル</label>
            <input id="title" type="text" name="title" value="">
          </div>
          <div>
            <label for="content">レビュー</label>
            <textarea id="content" name="content"></textarea>
          </div>
          <input type="submit" name="btn_submit" value="投稿">
        </form>
        <!-- ここにサクセスメッセージ -->
        <?php if( !empty($success_message) ): ?>
            <p class="success_message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- ここにエラーメッセージ -->
        <?php if( !empty($error_message) ): ?>
            <ul class="error_message">
              <?php foreach( $error_message as $value ): ?>
                <li>・<?php echo $value; ?></li>
              <?php endforeach; ?>
            </ul>
        <?php endif; ?>
      </div>

      <!-- レビュー一覧 -->
      <div class="review-boxes">
        <?php if( !empty($review_array) ): ?>
        <?php foreach($review_array as $value): ?>
          <div class="box">
            <h2><?php echo $value['title']; ?></h2>
            <p><?php echo $value['content']; ?></p>
            <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
          </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <!-- フッター部分 -->
  <footer>
    <div class="wrapper">
      <p class="copy-right"><small>&copy; 2021 Filmhouse</small></p>
    </div>
  </footer>
</body>
</html>