<?php

$ext = pathinfo($_FILES['upfile']['name']);

// $perm = ['pdf'];

class UploadFile {
  private $error;
  private $type;
  private $perm = ['pdf'];
  private $dest;

  public function __construct($error,$type,$ext,$dest) {
    $this->setError($error);
    $this->validation($error);
    $this->setType($type);
    $this->setExt($ext);
    $this->dest($dest);
    $this->typeCheck($type);
  }

  public function setError($error) {
    $this->uploadFile = $error;
  }

  public function setType($type) {
    $this->type = $type;
  }

  public function setExt($ext) {
    $this->ext = $ext;
  }

  public function setDest($dest) {
    $this->dest = $dest;
  }

  public function validation($error) {
    $msg = '';
    if($error !== UPLOAD_ERR_OK) {
      $msg = [
        UPLOAD_ERR_INI_SIZE   => 'php.iniのupload_max_filesize制限を超えています。',
        UPLOAD_ERR_FORM_SIZE  => 'HTMLのMAX_FILE_SIZE制限を超えています',
        UPLOAD_ERR_PARTIAL    => 'ファイルが一部しかアップロードされていません',
        UPLOAD_ERR_NO_FILE    => 'ファイルはアップロードされませんでした',
        UPLOAD_ERR_NO_TMP_DIR => '一時フォルダが存在しません',
        UPLOAD_ERR_CANT_WRITE => 'ディスクへの書き込みに失敗しました',
        UPLOAD_ERR_EXTENSION  => '拡張モジュールによってアップロードが中断されました',
      ];    

      return $err_msg = $msg[$error];
    }

    // } else {
    //   $src = $_FILES['upfile']['tmp_name'];
    //   // 本と違う書き方
    //   $dest = mb_convert_encoding($_FILES['upfile']['name'], 'UTF-8', "JIS, eucjp-win, sjis-win");
    
    //   if (!move_uploaded_file($src, 'pdf/'.$dest)) {
    //     $err_msg = 'アップロード処理に失敗しました';
    //   }
    // }
  }

  public function extCheck($ext,$perm) {
    if(!in_array(strtolower($ext['extension']), $perm)) {
      return $err_msg = 'PDF以外のファイルはアップロードできません';
    }
  }

  public function typeCheck($type) {
    if($type !== 'application/pdf') {
      return $err_msg = '拡張子を無理やりPDFにしないでください';
    }
  }

  public function getDest() {
    return $this->setDest;
  }

  public function dest($dest) {
    return $dest = mb_convert_encoding($dest, 'UTF-8', "JIS, eucjp-win, sjis-win");
  }


}
$a = new UploadFile($_FILES['upfile']['error'],$_FILES['upfile']['type'],pathinfo($_FILES['upfile']['name']),$_FILES['upfile']['name']);

// $a->validation();
// $a->extCheck();
// $a->typeCheck();
// エラー(コンストラクタで宣言したから)
// $a->dest($_FILES['upfile']['name']);


if (isset($err_msg)) {
  die($err_msg);
}

require_once 'DbManager.php';

try {
  $db = getDb();

  $sql = "INSERT INTO upload(
    name,date,ip,path,time
  ) VALUES (
    :name,date('now'),:ip,:path,time('now')
  )";

  $stmt = $db->prepare($sql);

  $dest = mb_convert_encoding($_FILES['upfile']['name'], 'UTF-8', "JIS, eucjp-win, sjis-win");

  $ip = $_SERVER['REMOTE_ADDR'];

  $params = [
    ':name' => $dest,
    ':ip'   => $ip,
    ':path' => 'pdf/' . $dest
  ];

  $stmt->execute($params);

} catch (PDOException $e) {
  echo $e->getMessage();
}

header('Location:' .dirname($_SERVER['PHP_SELF']).'/index.php' );

// 参考出典: 山田 祥寛著 独習PHP 第3版 P346-
// https://www.deep-blog.jp/engineer/archives/9603/
// https://www.flatflag.nir87.com/insert-942
?>