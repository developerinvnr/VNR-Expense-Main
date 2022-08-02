<?php

session_start();

session_destroy();
unset($_COOKIE['login']); setcookie('login', null, -1, '/'); 
unset($_COOKIE['EmployeeID']); setcookie('EmployeeID', null, -1, '/');
unset($_COOKIE['EmpCode']); setcookie('EmpCode', null, -1, '/');
unset($_COOKIE['Fname']); setcookie('Fname', null, -1, '/');
unset($_COOKIE['EmpRole']); setcookie('EmpRole', null, -1, '/');
unset($_COOKIE['CompanyId']); setcookie('CompanyId', null, -1, '/');
unset($_COOKIE['CheckLogin']); setcookie('CheckLogin', null, -1, '/');


if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}

header('location:index.php?msg=Logged Out Successfully&msgcolor=success');
?>