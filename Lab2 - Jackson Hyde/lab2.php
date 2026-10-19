<?php

// Lab 2A: Expressions and Control Structures
echo '<h1>Lab 2A: Expressions and Control Structures</h1>';

echo '<h2>1. Expressions and Operators</h2>';
$x = -5;
$y = 3 * (abs(2 * $x) + 4);
echo "The value of y is: $y<br>";

echo '<h2>2. Comparisons</h2><pre>';
var_dump(2 > 1);
var_dump(2 == 3);
var_dump('5' == 5);
var_dump('5' === 5);
echo '</pre>';

echo '<h2>3. Unary Operators</h2>';
// Chapter 4 slide 8: unary increment.
$a = 5;
$a++;
echo $a;
echo '<br>';

echo '<h2>4. Operator Precedence</h2>';
$result1 = 2 + 3 * 4;
$result2 = (2 + 3) * 4;
echo "2 + 3 * 4 = $result1<br>(2 + 3) * 4 = $result2<br>";

echo '<h2>5. Assignment Associativity</h2>';
$level = $score = $time = 0;
echo "Level: $level<br>Score: $score<br>Time: $time<br>";

echo '<h2>6. Equality and Assignment</h2><pre>';
$number = 5;
$sameNumber = 5;
$textNumber = '5';
$otherNumber = 6;
var_dump($number == $sameNumber);
var_dump($number === $sameNumber);
var_dump($number == $textNumber);
var_dump($number === $textNumber);
var_dump($number == $otherNumber);
var_dump($number === $otherNumber);
echo '</pre>';

echo '<h2>7. Logical Operators</h2><pre>';
$a = 1;
$b = 0;
var_dump($a && $b);
var_dump($a || $b);
var_dump(!$a);
var_dump(!$b);
echo '</pre>';

echo '<h2>8. Scholarship Decision with if...else</h2>';
$gpa = 3.59;
// Chapter 4 slide 21: if...else.
if ($gpa >= 3.7) {
    echo 'Student gets scholarship';
} else {
    echo 'No scholarship';
}

// Chapter 4 slide 22 structure, using the template's required 3.7/3.5 thresholds.
echo '<h2>9. Scholarship Decision with if...elseif...else</h2>';
if ($gpa >= 3.7) {
    echo 'Full scholarship';
} elseif ($gpa >= 3.5) {
    echo 'Partial scholarship';
} else {
    echo 'No scholarship';
}

echo '<h2>10. Access Status</h2>';
$status = 'pending';
switch ($status) {
    case 'approved':
        echo 'Access granted';
        break;
    case 'pending':
        echo 'Review in progress';
        break;
    default:
        echo 'Access denied';
        break;
}

echo '<h2>11. While Loop</h2>';
$fuel = 10;
while ($fuel > 1) {
    echo "There is enough fuel<br>";
    $fuel--;
}

echo '<h2>12. Multiplication Table</h2>';
// Chapter 4 slide 31.
for ($count = 1; $count <= 12; $count++) {
    echo $count . " times 12 is " .
        ($count * 12) . "<br>";
}
