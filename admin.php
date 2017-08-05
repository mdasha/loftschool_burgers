<?php
/**
 * Created by PhpStorm.
 * User: Dasha
 * Date: 03.08.2017
 * Time: 23:22
 */
//Создаем админку
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

//Выбираем таблицу clients базы данных Purchase для всех клиентов
function clients()
{
    $polnijmassiv = [];
    $sql = connection()->query("SELECT * FROM `clients`");

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

//Выбираем таблицу orders базы данных Purchase для всех заказов
function orders()
{
    $polnijmassiv = [];
    $sql = connection()->query("SELECT * FROM `orders`");

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



//Считываем массив из базы данных и превращаем его в строки таблицы (одинаково для таблицы clients и orders)
function tables_bd($array)
{
    foreach ($array as $v1) {
        echo '<tr>';
        foreach ($v1 as $v2) {
            echo '<td>' . $v2 . '</td>';
        }
        echo '</tr>';
    };
}

echo '<h1>Административная панель</h1>';
echo '<h2>Список всех клиентов</h2>';
$array = clients();

echo '<table border=\"1\">';
echo '<thead>
        <td>client_id</td>
        <td>email</td>
        <td>name</td>
        <td>phone</td>
';
tables_bd($array);

echo '</table>';

echo '<h2>Список всех заказов</h2>';
$array = orders();
echo '<table border=\"1\">';
echo '<thead>
        <td>order_id</td>
        <td>client_id</td>
        <td>Адрес</td>
        <td>Комментарии к заказу</td>
        <td>Потребуется сдача</td>
        <td>Оплата по карте</td>
        <td>Обратный звонок</td>
';
tables_bd($array);

echo '</table>';
