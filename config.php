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

    /* ��������� ���������� */
    if (mysqli_connect_errno()) {
        printf("������ ��� ���������� � ����� ������: %s\n", mysqli_connect_error());
        exit();
    };
    return $mysqli;
};
