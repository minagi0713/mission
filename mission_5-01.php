<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-01</title>
    </head>
    <body>

    <?php

        //データベース設定
        $dsn = '';
	    $user = '';
	    $password = '';
	    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    
        //テーブル作成
        $sql="CREATE TABLE IF NOT EXISTS mission5"
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."date TEXT,"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);


        //フォームから受け取り
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y/m/d H:i:s");
        $num = $_POST["num"];
        $pass = $_POST["pass"];
        $delete = $_POST["delete"];
        $pass_delete = $_POST["pass_delete"];
        $edit = $_POST["edit"];
        $pass_edit = $_POST["pass_edit"];

        //書き込み
        if(!empty($name && $comment && $pass && $num)){
            $id = $num;
            $sql ='UPDATE mission5 SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
            $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
            $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
            $stmt -> execute();
        }elseif(!empty($name && $comment && $pass)){
            $sql = $pdo->prepare("INSERT INTO mission5 (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();
        }

        //削除
        if(!empty($delete &&!empty($pass_delete))){
            $id = $delete;
            $sql ='SELECT * FROM mission5 WHERE id=:id';
            $stmt = $pdo->prepare($sql);                  
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute(); 
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($pass_delete == $row['pass']){
                    $sql = 'delete from mission5 WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }else{
                    echo "パスワードが違います";
                }
            }
        }

        //編集
        if (!empty($edit) &&!empty($pass_edit)){
            $id = $edit;
            $sql='SELECT * FROM mission5 WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();                         
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($pass_edit == $row['pass']){
                    $edit_name = $row['name'];
                    $edit_comment = $row['comment'];
                    $edit_num = $row['id'];
                }else{
                    echo "パスワードが違います";
                }
            }
        }

    ?>

    <form action="" method=post>
        
        【投稿フォーム】<br>
        <input type="hidden" name="num" value="<?php if(isset($edit_num)) {echo $edit_num;};?>">
        <input type="text" name="name" placeholder="名前" value="<?php if(isset($edit_name)) {echo $edit_name;};?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($edit_comment)) {echo $edit_comment;};?>"><br>
        <input type="text" name="pass" placeholder="パスワード">
        <input type="submit" name="submit" value="送信"><br><br>

        【削除フォーム】<br>
        <input type="text" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="pass_delete" placeholder="パスワード">
        <input type="submit" name="submit_delete" value="削除"><br><br>

        【編集フォーム】<br>
        <input type="text" name="edit" placeholder="編集番号"><br>
        <input type="text" name="pass_edit" placeholder="パスワード">
        <input type="submit" name="submit_edit" value="編集"><br><br>
    
    </form>    

    <?php
    
        //表示
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
        }

    ?>

    </body>
</html>