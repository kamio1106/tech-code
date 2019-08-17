<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
<body bgcolor="#33FFCC">
<form action="mission_5-1.php" method="post">




<?php
$date=new DateTime();//後で調べよう
$date=$date->format('Y-m-d H:i:s');//ここも

$dsn = 'mysql:dbname=tb210154db;host=localhost';//dsn構文
$user = 'tb-210154';
$password = '55evNcJV55';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//DBと接続


$sql = "CREATE TABLE IF NOT EXISTS tb5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
        . "date DATETIME,"
        . "password char(32)"
	.");";
	$stmt = $pdo->query($sql);


if(isset($_POST["edit"])){//編集ボタンが押された場合
 if((!empty($_POST["edit_number"])) and (!empty($_POST["edit_pass"]))){  
  $sql = 'SELECT * FROM tb5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る	
         if($row['id']==$_POST["edit_number"]){
  	  $edit_number_exist=1;
          if($row['password']==$_POST["edit_pass"]){
 	   $edit_number= $row['id'];
	   $edit_name = $row['name'];
           $edit_comment = $row['comment'];
           $edit_password = $row['password'];
          }
         }
        }
 }
}//edit送信時、該当する編集番号ありなら各値をedit_~~に代入、これを枠内に表示
//かつ編集送信か否かの指標とする。  

   

?>

<font size="6"><b>夏休みの思い出！<i>～Datebasever～<i/></b></font><br/><br/>
激熱な思い出を教えてください！<br>
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

//date_default_timezone_set('Asia/Tokyo');
//$time=date("Y/m/d H:i:s",$timestamp);

if(empty($edit_name) and empty($edit_comment) and empty($edit_number)){//not編集後送信
 if(isset($_POST["send"])){//送信ボタンが押された場合、
  if(empty($_POST["name"]) or empty($_POST["comment"]) or empty($_POST["password"])){
   echo "名前、コメント、パスワードを全て入力してください"."<br/>";
   echo "<br/>";
  }else{//名前、コメント、パス全部入力されているとき
   if(!empty($_POST["hidden_number"])){//編集送信
   
    $id =$_POST["hidden_number"]; //変更する投稿番号
	$name=$_POST["name"];
	$comment=$_POST["comment"]; 
        $password=$_POST["password"];//変更したい名前、変更したいコメントは自分で決めること
	$sql = 'update tb5 set name=:name,comment=:comment,password=:password where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

    echo"投稿が編集されました。"."<br/>";
    echo"<br/>";
   }else{//新規投稿

   $sql = $pdo -> prepare("INSERT INTO tb5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)"); 
	$sql -> bindParam(':name', $nam, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $commen, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $passwor, PDO::PARAM_STR);//時刻は文字列型。
	$nam = $_POST["name"];
	$commen = $_POST["comment"];
        $passwor = $_POST["password"]; //好きな名前、好きな言葉は自分で決めること
	$sql -> execute();
   }
  }
 }elseif(isset($_POST["delete"])){//削除のナンバーがある場合にその番号をテーブルから削除した後、ブラウザに表示する
  if(!empty($_POST["delete_number"])){//変数が存在し、空文字,0,nullなど以外である時にtrue
   if(!empty($_POST["del_pass"])){ ////色々、delpassが存在すれば色々、なければ入力してください。
   
    $sql = 'SELECT * FROM tb5';//削除番号存在時に$idに番号を代入
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る	
	 if($row['id']==$_POST["delete_number"]){
	  if($row['password']==$_POST["del_pass"]){
	   $id=$_POST["delete_number"];
	  }else{
           $del_num_exist=1;
	   echo "passwordが違います。"."<br/>";
           echo "<br/>";
          }
         }
        }
    if(!empty($id)){//削除する 
	$sql = 'delete from tb5 where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

     echo "投稿が削除されました。"."<br/>";
     echo "<br/>";
    }elseif(empty($del_num_exist)){
     echo "存在しない番号です"."<br/>";
     echo "<br/>";
    }
   }else{
    echo"passwordを入力してください"."<br/>";
    echo "<br/>";
   }
  }else{
   echo"削除番号が入力されていません"."<br/>";
   echo "<br/>";
  }
 }elseif(isset($_POST["edit"])){//editボタンを押したとき。
  if(!empty($_POST["edit_number"]) and !empty($_POST["edit_pass"])){
   if(!empty($edit_number_exist)){
    echo "passwordが違います。"."<br/>";
    echo "<br/>";
   }else{
    echo "存在しない番号です"."<br/>";
    echo "<br/>";
   }   
  }elseif(!empty($_POST["edit_number"])){
   echo "編集passwordを入力してください。"."<br/>";
   echo "<br/>";
  }else{
   echo "編集番号を入力してください。"."<br/>";
   echo "<br/>";
  }
 }else{
  echo "入力してください"."<br/>";
  echo "<br/>";
 }
}else{
 echo "編集後、送信ボタンを押してください"."<br/>";//編集後送信
 echo "<br/>";
}


$sql = 'SELECT * FROM tb5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
        echo "投稿履歴"."<br/>";
	echo "<hr>";
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'];
		echo $row['date'].'<br>';
	}
        echo "<hr>";


?>

</body>
</html>
