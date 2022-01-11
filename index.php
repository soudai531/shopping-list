<?php
require_once './dbmanager.php';
getDb();
$stms = getDb();
$sql = $stms->query('select * from shopping_list where complete_flag = 0 order by registration_date desc');
$result = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql = $stms->query('select * from shopping_list where complete_flag = 1 order by registration_date desc');
$complete_result = $sql->fetchAll(PDO::FETCH_ASSOC);
//追加ボタンが押されたら
if(isset($_POST['add'])){
    //入力チェック
    if(isset($_POST['name']) && !empty($_POST['name'])){
        $sql = $stms->prepare('insert into shopping_list(name,category_id,place_id) values(?,?,?)');
        $sql->bindValue(1,$_POST['name']);
        $sql->bindValue(2,$_POST['category']);
        $sql->bindValue(3,$_POST['place']);
        $sql->execute();
        $stmt = null;
        header('Location: ./');
        exit;
    }
}

//カテゴリーテーブル
$sql = $stms->query('select * from category');
$category_result = $sql->fetchAll(PDO::FETCH_ASSOC);

//場所テーブル
$sql = $stms->query('select * from place');
$place_result = $sql->fetchAll(PDO::FETCH_ASSOC);






//削除ボタンが押されたら
if(isset($_POST['delete'])){
    $sql = $stms->prepare('delete from shopping_list where id=?');
    $sql->bindValue(1,$_POST['delete']);
    $sql->execute();
    $stmt = null;
    header('Location: ./');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>買い物リスト</title>
</head>
<body>
<h1>買い物リスト</h1>
<form action="index.php" method="post">
    <span>品名</span>
    <input type="text" name="name"/>
    <span>カテゴリー：</span>
    <select name="category">
        <?php foreach($category_result as $row):?>
        <option value="<?= $row['category_id']?>"><?= $row['category_name'] ?></option>
        <?php endforeach; ?>
    </select>
    <span>場所：</span>
    <select name="place">
        <?php foreach($place_result as $row):?>
        <option value="<?= $row['place_id']?>"><?= $row['place_name'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="add">追加</button>
</form>
<?php
foreach($result as $row):?>
<div class="item-row">
    <form action="index.php" method="post">
        <button type="submit" name="complete" value="<?= $row['id'] ?>"><img src="./img/checkbox_0.png" width="15px"/></button>
        <input type="checkbox" name="complete" value="<?= $row['id'] ?>">
        <span><?= $row['name']?></span>
        <span><?= $row['category_id']?></span>
        <span><?= $row['place_id']?></span>
        <button type="submit" name="delete" value="<?= $row['id'] ?>">削除</button>
    </form>
</div>
<?php endforeach; ?>
<div class="complete-row">
    <?php foreach($complete_result as $row):?>
    <div class="item-row">
        <form action="index.php" method="post">
            <button type="submit" name="uncomplete" value="<?= $row['id'] ?>"><img src="./img/checkbox_1.png" width="15px"/></button>
            <span><?= $row['name']?></span>
            <span><?= $row['category_id']?></span>
            <span><?= $row['place_id']?></span>
            <button type="submit" name="delete" value="<?= $row['id'] ?>">削除</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
