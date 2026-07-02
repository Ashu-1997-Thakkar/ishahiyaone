<?php
session_start();

$num1 = rand(1, 9);
$num2 = rand(1, 9);
$operators = ['+', '-', '*'];
$operator = $operators[array_rand($operators)];

$captcha_result = 0;
switch ($operator) {
    case '+': $captcha_result = $num1 + $num2; break;
    case '-': $captcha_result = $num1 - $num2; break;
    case '*': $captcha_result = $num1 * $num2; break;
}

$_SESSION['captcha'] = $captcha_result;
$_SESSION['captcha_question'] = "$num1 $operator $num2";

echo $_SESSION['captcha_question'];
?>
