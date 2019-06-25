<?php

require_once('class/uploadFile.php');
require_once('function/getIpAddress.php');

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