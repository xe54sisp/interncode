<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
        $dsn ='mysql:dbname = **********;host=localhost'; //データベース名
        $user ='**********';          //ユーザー名
        $password = '***********';    //パスワード
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $deletenum = $_POST["deletenum"];
        $editnum = $_POST["editnum"];
        $second = $_POST["second"];
        $pass = $_POST["pass"];
        $deletepass = $_POST["deletepass"];
        $editpass = $_POST["editpass"];
        $name = $_POST["name"];
        $text = $_POST["text"];
        $date = date("Y/m/d H:i:s");
        //掲示板に名前とコメントを追加、もしくは編集し、送信するとき
        if($name != "" && $text != "" && $pass != ""){
            //一度目の新規送信のとき
            if ($second == ""){
                $sql = $pdo -> prepare("INSERT INTO phptest(name,text,date,pass)VALUES(:name, :text, :date, :pass)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':text', $text, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $sql -> execute();
            }
            //二度目の編集した後投稿するとき
            else{
                $id = $second; 
                $sql = 'UPDATE phptest SET name=:name,text=:text,date=:date WHERE id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':text', $text, PDO::PARAM_STR);
	            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
                $second = "";
            }
        }
        //番号を指定し削除するとき
        elseif($deletenum != "")
        {
            $dpass = "";
            $id = $deletenum;
            $sql = 'SELECT * FROM phptest WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();                            
            $results = $stmt->fetchAll(); 
	        foreach ($results as $row){
		        $dpass = $row['pass'];
		        
            }
            if($dpass == $deletepass){
                $sql = 'delete from phptest where id=:id';
    	        $stmt = $pdo->prepare($sql);
    	        $stmt->bindParam(':id', $deletenum, PDO::PARAM_INT);
    	        $stmt->execute();
            }
        }
        //編集番号を受け取り、その編集の内容を受け取る機能
        elseif($editnum != ""){
            $id = $editnum;
            $second = "";
            $ename = "";
            $etext = "";
            $epass = "";
            $sql = 'SELECT * FROM phptest WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute();                             
            $results = $stmt->fetchAll(); 
	        foreach ($results as $row){
		        $epass = $row['pass'];
		        if($epass == $editpass){
    		        $second = $row['id'];
    		        $ename = $row['name'];
    		        $etext = $row['text'];
		        }
            }
        }
    ?>
    <form action="" method="post">
        名前
        <input type="text" name = "name" value = "<?php echo "$ename"; ?>">
        コメント
        <input type="text" name = "text" value = "<?php echo "$etext"; ?>">
         パスワード（初回の方は記録しておいてください）
        <input type="text" name = "pass"　placeholder = "パスワード">
        <input type="submit" name="submit">
        <input type = "hidden" name = "second" value = "<?php echo "$second"; ?>">
    </form>
    <form action="" method="post">
        <input type = "number" name = "deletenum" placeholder = "削除対象番号">
        <input type="text" name = "deletepass" placeholder = "パスワード">
        <input type = "submit" name = "delete" placeholder = "削除">
    </form>
    <form action="" method="post">
        <input type = "number" name = "editnum" placeholder = "編集対象番号">
        <input type = "text" name = "editpass"　placeholder = "パスワード">
        <input type = "submit" name = "edit" placeholder = "編集">
    </form>
</body>