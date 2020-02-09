<?php
//CREATE DATABASE library;
//USE library;
//CREATE TABLE IF NOT EXISTS library(
//    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY UNIQUE,
//    `title` VARCHAR(100) NOT NULL,
//    `text` TEXT NOT NULL
//);
//
//INSERT INTO library (title, `text`) VALUES ('HTML', 'HTML — стандартизированный язык разметки документов во Всемирной паутине. Большинство веб-страниц содержат описание разметки на языке HTML. Язык HTML интерпретируется браузерами; полученный в результате интерпретации форматированный текст отображается на экране монитора компьютера или мобильного устройства.');
//INSERT INTO library (title, `text`) VALUES ('CSS', 'CSS — формальный язык описания внешнего вида документа, написанного с использованием языка разметки. Преимущественно используется как средство описания, оформления внешнего вида веб-страниц, написанных с помощью языков разметки HTML и XHTML.');
//INSERT INTO library (title, `text`) VALUES ('PHP', 'PHP — скриптовый язык общего назначения, интенсивно применяемый для разработки веб-приложений. В настоящее время поддерживается подавляющим большинством хостинг-провайдеров и является одним из лидеров среди языков, применяющихся для создания динамических веб-сайтов.');
//INSERT INTO library (title, `text`) VALUES ('JS', 'JavaScript — мультипарадигменный язык программирования. Поддерживает объектно-ориентированный, императивный и функциональный стили. Является реализацией языка ECMAScript. JavaScript обычно используется как встраиваемый язык для программного доступа к объектам приложений.');
$server = '127.0.0.1';
$db = 'library'; // имя базы данных
$login = 'user'; // пользователь
$pwd = 12344321; // пароль
$charset = 'utf8'; // кодировка

$dsn = "mysql:host=$server;dbname=$db;charset=$charset";
$options = [ // это массив с дополнительными опциями (не обязательно), для подключения
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, // это показывает ошибки при подключении
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC  // данные из бд получаем в виде ассоциативного массива
];
$pdo = new PDO($dsn, $login,$pwd,$options);
$sql = "SELECT * FROM library";
$statement = $pdo->query($sql);
$data = $statement->fetchAll();
//var_dump($data);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Книги</title>
</head>
<body>
<form method="post">
    <label>Поиск по названию файла:
        <input type="text" name="search_title" placeholder="Search">
        <input type="submit" name="submit_title"  value="Начать поиск">
    </label>
    <br>
    <br>
    <label>Поиск по содержимому файла:
        <input type="text" name="search_text" placeholder="Search">
        <input type="submit" name="submit_text"  value="Начать поиск">
    </label>
</form>
<?// foreach ($data as $book): ?>
<!--<h1>--><?// echo $book['title']; ?><!--</h1>-->
<!--<div>-->
<!--    <p>--><?// echo $book['text']; ?><!--</p>-->
<!--</div>-->
<?// endforeach; ?>
<?//
if(isset($_POST['submit_title'])) {
    $search = $_POST['search_title'];
    //var_dump($search);
    $user_sql = "SELECT * FROM library WHERE title = ?";
    $preparedStatement = $pdo->prepare($user_sql);
    $preparedStatement->bindParam(1,$search);
    $preparedStatement->execute();
    $data1 = $preparedStatement->fetch();
    // var_dump($data1);
}
?>
<h1><? echo $data1['title']; ?></h1>
<div>
    <p><? echo $data1['text']; ?></p>
</div>

<?
if(isset($_POST['submit_text'])) {
    $search_text = $_POST['search_text'];
    //var_dump($search_text);
    $l= mysqli_connect('127.0.0.1', 'user', '12344321', 'library');
    $search_text = trim($search_text);
    $q= mysqli_query($l, "SELECT * FROM `library` WHERE `text` LIKE '%$search_text%'");
    //var_dump($q);
    $result=mysqli_fetch_assoc($q);
    //var_dump($result);
    $new_data1 = $result;
    //var_dump($new_data1);
    $text = array_pop($new_data1);
    //var_dump($text);
    $res = strripos ($text, $search_text);
    //var_dump($res);
    mysqli_free_result($q);
    mysqli_close($l);
}
?>
<h1><? echo $result['title']; ?></h1>
<div>
    <p><? echo $result['text']; ?></p>
</div>
<?php
    if ($res === false) {
        echo "Строка '$search_text' не найдена";
}
    if ($res !== false) {
        echo "Строка '$search_text' найдена в позиции $res.";
}
?>
</body>
</html>