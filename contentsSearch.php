<?php
// 検索の実行結果を書く
?>


<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>PDF投稿フォーム</title>
  </head>
  <body>
    <h1>PDF共有画面</h1>
      
    <!-- 検索フォーム -->
			<div class="searchForm">
				<form action="contentsSearch.php" method="POST">
					<input type="text" name="search" class="textArea" placeholder="" required>
					<input type="submit" value="検索" class="submitArea">
				</form>
      </div>
      
    <?php 
     ?>

    <table class="table table-striped table-hove table-responsiver">
      <thead>
        <tr>
          <th>No.</th>
          <th>NAME</th>
          <th>DATE</th>
          <th>TIME</th>
          <th>IP</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($res as $value) :?>
          <tr>
            <th><?= $value['id'] ?></th>
            <th><a href="<?= $value['path'] ?>"><?= $value['name'] ?></a></th>
            <th><?= $value['date'] ?></th>
            <th><?= $value['time'] ?></th>
            <th><?= $value['ip'] ?></th>
            <th>編集</th>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php for ($x=1; $x <= $pagination ; $x++) : ?>
	    <a href="?page=<?= $x ?>"><?= $x; ?></a>
    <?php endfor ?>
    
    <div>
      <p><a href="upload.html">アップロードフォームへ移動</a></p>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>