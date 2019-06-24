<?php

class UploadFile {
  private $file;
  private $errors = [];
  private $ext;
  private $src;
  private $dest;

  const   PERM = ['pdf'];

  // $_FILES[$name]の存在チェック(のはず?)
  public function __construct($name) 
  {
    $this->file = isset($_FILES[$name]) ? $_FILES[$name] : null;
    $this->ext = pathinfo($this->file['name']);
    
    // 三項演算子
    // https://www.php.net/manual/ja/language.operators.comparison.php#language.operators.comparison.ternary
    // $_FILES[$name]がissetであれば $this->file = $_FILES[$name]としてissetでなければ$this->file =nullとする
  }
  // 想定では、$this->file は $_FILES['upfile']となっているつもり

  /**
   * 指定されたアップロードファイルのバリデーション判定
   * 
   * return bool true: エラーなし, false: エラーあり
   * 
   */
  public function valid()
  {
    // エラーチェック。エラーがある場合、$this->errorsにエラーメッセージを追加する
    // エラーなしの場合、return true。エラーがある場合、return false

    if($this->file['error'] !== UPLOAD_ERR_OK ) 
    {
      $msg = [
        UPLOAD_ERR_INI_SIZE   => 'php.iniのupload_max_filesize制限を超えています。',
        UPLOAD_ERR_FORM_SIZE  => 'HTMLのMAX_FILE_SIZE制限を超えています',
        UPLOAD_ERR_PARTIAL    => 'ファイルが一部しかアップロードされていません',
        UPLOAD_ERR_NO_FILE    => 'ファイルはアップロードされませんでした',
        UPLOAD_ERR_NO_TMP_DIR => '一時フォルダが存在しません',
        UPLOAD_ERR_CANT_WRITE => 'ディスクへの書き込みに失敗しました',
        UPLOAD_ERR_EXTENSION  => '拡張モジュールによってアップロードが中断されました',
      ];
      $this->errors = $msg[$this->file['error']];
      return false;
    } 
    elseif (!in_array(strtolower($this->ext['extension']), self::PERM)) 
    {
      $this->errors = 'PDF以外のファイルはアップロードできません';
      return false;
    } 
    elseif ($this->file['type'] !== 'application/pdf') 
    {
      $this->errors = '拡張子を無理にPDFに変換しないでください';
      return false;
    } 
    else 
    {
      $src = $this->file['tmp_name'];
      $dest = mb_convert_encoding($this->file['name'], 'UTF-8', 'JIS, eucjp-win, sjis-win');

      if (!move_uploaded_file($src, 'pdf/' . $dest)) 
      {
        $this->errors = 'アップロード処理に失敗しました';
        return false;
      } 
    } 
    return true;
  }

  /**
   * 保存したファイルのパスを返却する
   */
  public function desc()
  {
    // $this->file['name']の文字コードを'UTF-8'に変換する
    return $this->path = mb_convert_encoding($this->file['name'], 'UTF-8');
  }
  
  public function getErrors()
  {
    return $this->errors;
  }


}

function getIpAddress() 
{
  if(!is_null($_SERVER['REMOTE_ADDR'])) 
  {
    return $_SERVER['REMOTE_ADDR'];
  }
}

$file = new UploadFile('upfile');

if($file->valid())
{
  // エラーがなければDBに登録
  require_once 'DbManager.php';
  
  try 
  {
    $db = getDb();
  
    $sql = "INSERT INTO upload(
      name,date,ip,path,time
    ) VALUES (
      :name,date('now'),:ip,:path,time('now')
    )";
  
    $stmt = $db->prepare($sql);
  
    $params = [
      ':name' => $file->desc(),
      ':ip'   => getIpAddress(),
      ':path' => 'pdf/' . $file->desc(),
    ];
  
    $stmt->execute($params);
  
  } 
  catch (PDOException $e) 
  {
    echo $e->getMessage();
  }
  
  header('Location:' .dirname($_SERVER['PHP_SELF']).'/index.php' );  
  
} 
else 
{
  echo $file->getErrors();
}

// 参考出典: 山田 祥寛著 独習PHP 第3版 P346-
// https://www.deep-blog.jp/engineer/archives/9603/
// https://www.flatflag.nir87.com/insert-942
?>