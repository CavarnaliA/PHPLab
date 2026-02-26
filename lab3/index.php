<?php
$day = date('N');

if ($day == 1 || $day == 3 || $day == 5) {
    $johnSchedule = "8:00 - 12:00";
} else {
    $johnSchedule = "Нерабочий день";
}

if ($day == 2 || $day == 4 || $day == 6) {
    $janeSchedule = "12:00 - 16:00";
} else {
    $janeSchedule = "Нерабочий день";
}
?>

<style>
table {
    border-collapse: collapse;
}
th, td {
    padding: 6px 10px;
    text-align: left;
}
</style>

<table border="1" cellpadding="0" cellspacing="0">
    <tr>
        <th>№</th>
        <th>Фамилия Имя</th>
        <th>График работы</th>
    </tr>
    <tr>
        <td>1</td>
        <td>John Styles</td>
        <td><?= $johnSchedule ?></td>
    </tr>
    <tr>
        <td>2</td>
        <td>Jane Doe</td>
        <td><?= $janeSchedule ?></td>
    </tr>
</table>

<?php

echo "<br><br>";

$a = 0;
$b = 0;

for ($i = 0; $i <= 5; $i++) {
    $a += 10;
    $b += 5;
    echo "Шаг $i: a = $a, b = $b <br>";
}

echo "Конец for-цикла! <br><br>";


$a = 0;
$b = 0;
$i = 0;

while ($i <= 5) {
    $a += 10;
    $b += 5;
    echo "Шаг $i: a = $a, b = $b <br>";
    $i++;
}

echo "Конец while-цикла! <br><br>";


$a = 0;
$b = 0;
$i = 0;

do {
    $a += 10;
    $b += 5;
    echo "Шаг $i: a = $a, b = $b <br>";
    $i++;
} while ($i <= 5);

echo "Конец do_while-цикла! <br><br>";
?>