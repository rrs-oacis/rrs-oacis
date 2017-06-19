<?php
echo ok;
$pdo = new PDO('sqlite:../data/main.db');
$sth = $pdo->prepare("select value from system where name=:name;");
$sth->bindValue(':name', 'test', PDO::PARAM_STR);
$sth->execute();
while($row = $sth->fetch(PDO::FETCH_ASSOC))
{
    echo $row['value'];
}
?>
