<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
<body bgcolor="#33FFCC">
<form action="mission_3-5.php" method="post">
<?php
$filename= "mission_3-5.txt";
if(isset($_POST["edit"])){//編集ボタンが押された場合
 if((!empty($_POST["edit_number"])) and (!empty($_POST["edit_pass"]))){
  if(file_exists($filename)){
   $file=file($filename);
   foreach($file as $line){//編集name,commentを取得
    $date=explode("<>",$line);
    if($date[0]==$_POST["edit_number"]){
     if($date[4]==$_POST["edit_pass"]){
      $edit_number=$date[0];
      $edit_name=$date[1];
      $edit_comment=$date[2];
      $edit_pass=$date[4];
     
}}}}}}//編集ボタン送信時かつ番号あり、ファイル存在、なら各$edit番号取得
?>

<font size="6"><b>趣味を語ろう！</b></font><br/><br/>
自分の趣味について熱弁してみましょう！<br>
名前、コメント、パスワードを入力後、送信ボタンを押してください。<br/>
送信後の削除、編集も可能です。<br/><br/>
<b>送信用</b><br/>
名前:<input type="text" name="name" size="30" value="<?php if(!empty($edit_name)){echo $edit_name;}else{echo"";}  ?>" /><br />
コメント:<input type="text" name="comment" size="30" value="<?php if(!empty($edit_comment)){echo $edit_comment;}else{echo"";}  ?>" /><br/>
pass:<input type="text" name="password" size="30" value=""/>
<input type="submit" name="send" value="送信" /><br/><br/>
<input type="hidden" name="hidden_number" size="30" value="<?php if(!empty($edit_number)){echo $edit_number;}else{echo"";}  ?>" /><br/>
<b>削除用</b><br/>
削除番号:<input type="text" name="delete_number" size="30" value="" /><br/>
pass:<input type="text" name="del_pass" size="30" value=""/>
<input type="submit" name="delete" value="削除" /><br/><br/><br/>
<b>編集用</b><br/>
編集番号:<input type="text" name="edit_number" size="30" value="" /><br/>
pass:<input type="text" name="edit_pass" size="30" value=""/>
<input type="submit" name="edit" value="編集" /><br/><br/><br/>
</form>

<?php 
$filename="mission_3-5.txt";
date_default_timezone_set('Asia/Tokyo');
$timestamp=time();
$time=date("Y/m/d H:i:s",$timestamp);
if(empty($edit_name) and empty($edit_comment) and empty($edit_number)){//今回送信or削除を入力したor編集番号不適切
 if(isset($_POST["send"])){//送信ボタンが押された場合
  if(empty($_POST["name"]) or empty($_POST["comment"]) or empty($_POST["password"])){
   echo "名前、コメント、パスワードを全て入力してください"."<br/>";
   echo "<br/>";
   $file=file($filename);
   foreach($file as $line){
    $date=explode("<>",$line);
    for($i=0;$i<=3;$i++){  
     echo $date[$i]."\n";
    }echo "<br/>";
   }  
  }else{//name,comment.passが入力   
   if(!empty($_POST["hidden_number"])){//編集送信    
    $file=file($filename);
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $pass=$_POST["password"];
    $hidden_number=$_POST["hidden_number"];
    $linenumber=count($file)-1;//負の数にはなりえない    
    $lastarray=$file[$linenumber];
    $lastarray_array=explode("<>",$lastarray);
    $lastnumber=$lastarray_array[0]+1;//投稿numberが決まる。
    $fp = fopen($filename ,"w");
    foreach($file as $line){
     $date=explode("<>",$line);
     if($date[0]!=$hidden_number){  
      fwrite($fp,$date[0]."<>".$date[1]."<>".$date[2]."<>".$date[3]."<>".$date[4]."<>"."\n");
     }else{
      fwrite($fp,$hidden_number."<>".$name."<>".$comment."<>".$time."<>".$pass."<>"."\n");//書き込み
     }
    }
    fclose( $fp );//ファイルのedit完了
  
    echo"投稿が編集されました。"."<br/>";
    echo"<br/>";
    $filename="mission_3-5.txt";//直前の投稿内容取得のため再度読み込み
    $file=file($filename);
    foreach($file as $line){
     $date=explode("<>",$line);
     for($i=0;$i<=3;$i++){  
      echo $date[$i]."\n";
     }echo "<br/>";  
    }
   }else{//新規投稿
    $name=$_POST["name"];
    $comment=$_POST["comment"];
    $pass=$_POST["password"];
    if(file_exists($filename)){
     $file=file($filename);
     $linenumber=count($file)-1;//fileはあるが投稿削除されている場合、-1が起こりうる。後で修正。
     if($linenumber!=-1){
      $lastarray=$file[$linenumber];
      $lastarray_array=explode("<>",$lastarray);
      $lastnumber=$lastarray_array[0]+1;
     }else{
      $lastnumber=1;
     }
    }else{
     $lastnumber=1;
    }//投稿numberが決まる。  

    $fp = fopen($filename ,"a");
    fwrite( $fp,$lastnumber."<>".$name."<>".$comment."<>".$time."<>".$pass."<>"."\n");
    fclose( $fp );//テキストファイルに書き込む 
    echo "新規投稿を受け付けました。"."<br/>";
    echo "<br/>";
    
    $filename="mission_3-5.txt";//直前の投稿内容取得のため再度読み込み
    $file=file($filename);
    foreach($file as $line){
     $date=explode("<>",$line);
     for($i=0;$i<=3;$i++){  
      echo $date[$i]."\n";
     }echo "<br/>";
  }}}
 }elseif(isset($_POST["delete"])){//削除ボタンが押された場合
  if(!empty($_POST["delete_number"])){//変数が存在し、空文字,0,nullなど以外である時にtrue
   if(!empty($_POST["del_pass"])){
    if(file_exists($filename)){
     $file=file($filename);  
     foreach($file as $line){
      $date=explode("<>",$line);
      if($date[0]==$_POST["delete_number"]){  
       $delpassword=$date[4];
      }
     }
     if(!empty($delpassword)){
      if($delpassword==$_POST["del_pass"]){
       echo "投稿が削除されました。"."<br/>";
       echo "<br/>";
       foreach($file as $line){
        $date=explode("<>",$line);
        if($date[0]!=$_POST["delete_number"]){  
         for($i=0;$i<=3;$i++){  
          echo $date[$i]."\n";
         }echo "<br/>";//
        }
       }
      }else{
       echo "passwordが違います。"."<br/>";
       echo "<br/>";
       $file=file($filename);
       foreach($file as $line){
        $date=explode("<>",$line);
        for($i=0;$i<=3;$i++){  
         echo $date[$i]."\n";
        }echo "<br/>";  
       }
      }
     }else{
      echo "存在しない番号です"."<br/>";
      echo"<br/>"; 
      $file=file($filename);
      foreach($file as $line){
       $date=explode("<>",$line);
       for($i=0;$i<=3;$i++){  
        echo $date[$i]."\n";
       }echo "<br/>";  
      }
     }
     $fp = fopen($filename ,"w");
     foreach($file as $line){
      $date=explode("<>",$line);
      if($date[0]!=$_POST["delete_number"]){  
       fwrite($fp,$date[0]."<>".$date[1]."<>".$date[2]."<>".$date[3]."<>".$date[4]."<>"."\n");
      }
     }
     fclose( $fp );
    }else{
     echo "存在しない番号です";
    }
   }else{
    echo "passwordが入力されていません。"."<br/>";
    echo "<br/>";
    $file=file($filename);
    foreach($file as $line){
     $date=explode("<>",$line);
     for($i=0;$i<=3;$i++){  
      echo $date[$i]."\n";
     }echo "<br/>";  
    }
   }
  }else{
   echo "削除番号が入力されていません。" ."<br/>";
   echo "<br/>";
   $file=file($filename);
   foreach($file as $line){
    $date=explode("<>",$line);
    for($i=0;$i<=3;$i++){  
     echo $date[$i]."\n";
    }echo "<br/>";  
   }
  }  
 }elseif(isset($_POST["edit"])){//editボタンを押したとき。
  if(!empty($_POST["edit_number"]) and !empty($_POST["edit_pass"])){
   echo "編集番号を入力してください"."<br/>";
   echo "<br/>";
   $file=file($filename);
   foreach($file as $line){
    $date=explode("<>",$line);
    for($i=0;$i<=3;$i++){  
     echo $date[$i]."\n";
    }echo "<br/>";  
   }
  }elseif(!empty($_POST["edit_number"])){
   echo "編集passwordを入力してください。"."<br/>";
   echo "<br/>";
   $file=file($filename);
   foreach($file as $line){
    $date=explode("<>",$line);
    for($i=0;$i<=3;$i++){  
     echo $date[$i]."\n";
    }echo "<br/>";  
   }
  }else{
   echo "編集番号を入力してください。"."<br/>";
   echo "<br/>";
   $file=file($filename);
   foreach($file as $line){
    $date=explode("<>",$line);
    for($i=0;$i<=3;$i++){  
     echo $date[$i]."\n";
    }echo "<br/>";  
   }
  }
 }
 else{
  echo "入力してください";
 }
}else{
 echo "編集後、送信ボタンを押してください";
}

?>

</body>
</html>
