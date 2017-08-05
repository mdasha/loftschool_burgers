<?php
/**
 * Created by PhpStorm.
 * User: Dasha
 * Date: 01.08.2017
 * Time: 20:50
 */
//Экранируем спецсимволы, записываем введенные данные в массив $data
$data['name'] = htmlspecialchars($_POST['name']);
$data['phone'] = htmlspecialchars($_POST['phone']);
$data['email'] = htmlspecialchars($_POST['email']);
$data['address'] = htmlspecialchars('Улица '.$_POST['street'].' , дом '. $_POST['home'].
    ' , корпус '.$_POST['part'].' , квартира '.$_POST['flat'].' , этаж '.$_POST['floor']);
$data['comment'] = htmlspecialchars($_POST['comment']);
$data['payment'] = htmlspecialchars($_POST['payment']);
$data['card'] = htmlspecialchars($_POST['card']);
$data['callback'] = htmlspecialchars($_POST['callback']);
$json = array(); // пoдгoтoвим мaссив oтвeтa

// Проверяем, заполнены ли все необходимые поля в форме (
if (!$data['name'] or !$data['phone']or !$data['email'] or !$_POST['street'] or !$_POST['home']) {
    // eсли хoть oднo пoлe oкaзaлoсь пустым
    $json['error'] = 'Вы зaпoлнили нe всe пoля! Мы не можем осуществить доставку. Заполните, пожалуйста,
    все поля'; // пишeм oшибку в мaссив
    echo json_encode($json); // вывoдим мaссив oтвeтa
    die(); // умирaeм
}
// Проверка email на валидность
if (!preg_match("|^[-0-9a-z_\.]+@[-0-9a-z_^\.]+\.[a-z]{2,6}$|i", $data['email'])) { // прoвeрим email нa вaлиднoсть
    $json['error'] = 'Нeвeрный фoрмaт email! >_<'; // пишeм oшибку в мaссив
    echo json_encode($json); // вывoдим мaссив oтвeтa
    die(); // умирaeм
}

// echo json_encode($data);

//Подключаемся к базе данных Purchase
function connection()
{
    $mysqli = mysqli_connect("localhost", "root", "", "Purchase");

    /* проверяем соединение */
    if (mysqli_connect_errno()) {
        printf("Ошибка при соединении с базой данных: %s\n", mysqli_connect_error());
        exit();
    };
    return $mysqli;
};

//Выбираем таблицу clients базы данных Purchase для клиентов с введенным email
function clients()
{
    $email = $_POST['email'];
    $polnijmassiv = [];
    $sql = connection()->query("SELECT * FROM `clients` where `email` LIKE '$email' ");

// Преобразуем полученные данные в двумерный массив
    $j = 0;
    $j1 = 0;
    while ($rows = $sql->fetch_array(MYSQLI_NUM)) {
        for ($i = 0; $i <= 3; $i++) {
            $aaa[$j] = $rows[$i];
            $polnijmassiv[$j1][$i] = $aaa[$j];
            $j++;
        }
        $j1++;
    }
    return $polnijmassiv;
};
// Выбираем заказы одного клиента
function orders()
{
    $client_id = clients()[0][0];
    $polnijmassiv = [];
    $sql = connection()->query("SELECT * FROM `orders` where `client_id` LIKE '$client_id' ");

// Преобразуем полученные данные в двумерный массив
    $j = 0;
    $j1 = 0;
    while ($rows = $sql->fetch_array(MYSQLI_NUM)) {
        for ($i = 0; $i <= 6; $i++) {
            $aaa[$j] = $rows[$i];
            $polnijmassiv[$j1][$i] = $aaa[$j];
            $j++;
        }
        $j1++;
    }
    return $polnijmassiv;
};

// echo "Зарегистрировано клиентов с тем же email: ". count(clients());

// Если записей с таким email нет, то записываем данные о пользователе
if (clients()[0][0]==0 and $data['email']<>"") {
        $result = connection()->query("INSERT INTO `clients` (`email`, `name`, `phone`) 
                        VALUES ('".$_POST['email']."','".$_POST['name']."','".$_POST['phone']."')");
       // echo "Спасибо за регистрацию 2".$data['email']."Ваш id". clients()[0][0] ;
} else {
  //  echo "<br>Вы уже зарегистрированы, ваш id: ". clients()[0][0];
};
// Вносим данные о заказе в таблицу с заказами  orders
    $order = connection()->query("INSERT INTO `orders` (`client_id`, `address`, `comment`, `payment`,`card`,`recall`) 
                        VALUES ('" . clients()[0][0] . "','" . $data['address'] . "','" . $data['comment'] .
        "','" . $data['payment'] . "','" . $data['card'] . "','" . $data['callback'] . "')");
// Записываем данные о заказе в файл
// Создаем папку с именем - временем отправки файла
    $dir_name = date('d\-m\-Y\-H\-i\-s');
    mkdir($dir_name, 0700);
    $file = $dir_name.'/order.html';
    $order = '<h1>Заказ №'.orders()[count(orders())-1][0].'</h1>';
    $order .= 'Ваш заказ будет доставлен по адресу: '.orders()[count(orders())-1][2];
    $order .= '<br>Ваш заказ: DarkBeefBurger за 500 рублей, 1 шт';
    if (count(orders())==1) {
        $order .= '<br>Спасибо. Это Ваш первый заказ!';
    } else {
        $order .= '<br>Спасибо. Это уже '.count(orders()).' заказ!';
    }
    file_put_contents($file, $order);
//Если ошибок не было, то записываем так
   $json['error'] = 0; // oшибoк нe былo

    echo json_encode($json); // вывoдим мaссив oтвeтa
