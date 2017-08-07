<?php
/**
 * Created by PhpStorm.
 * User: Dasha
 * Date: 07.08.2017
 * Time: 18:31
 */
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
