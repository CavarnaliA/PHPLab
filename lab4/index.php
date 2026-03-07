<?php

declare(strict_types=1);

$transactions = [
    [
        "id" => 1,
        "date" => "2026-01-25",
        "amount" => 1000.00,
        "description" => "Shopping",
        "merchant" => "ZityMall",
    ],
    [
        "id" => 2,
        "date" => "2026-02-03",
        "amount" => 500.00,
        "description" => "Sushi",
        "merchant" => "Samurai Sushi",
    ],
    [
        "id" => 3,
        "date" => "2026-02-14",
        "amount" => 250.00,
        "description" => "Lunch with a friend",
        "merchant" => "Andy's Pizza",
    ],
    [
        "id" => 4,
        "date" => "2026-02-20",
        "amount" => 400.00,
        "description" => "Groceries",
        "merchant" => "№1 Supermarket",
    ],
    [
        "id" => 5,
        "date" => "2026-02-25",
        "amount" => 150.00,
        "description" => "Cinema",
        "merchant" => "Cineplex",
    ],
    [
        "id" => 6,
        "date" => "2026-03-01",
        "amount" => 500.00,
        "description" => "Gym",
        "merchant" => "Bigsport Gym",
    ],
    [
        "id" => 7,
        "date" => "2026-03-05",
        "amount" => 300.00,
        "description" => "Books",
        "merchant" => "Bookstore",
    ],
];

function calculateTotalAmount(array $transactions): float
{
    $total = 0;

    foreach ($transactions as $transaction) {
        $total += $transaction["amount"];
    }

    return $total;
}

function findTransactionByDescription(string $descriptionPart): ?array
{
    global $transactions;

    foreach ($transactions as $transaction) {
        if (stripos($transaction["description"], $descriptionPart) !== false) {
            return $transaction;
        }
    }

    return null;
}

function findTransactionById(int $id): ?array
{
    global $transactions;

    foreach ($transactions as $transaction) {
        if ($transaction["id"] === $id) {
            return $transaction;
        }
    }

    return null;
}

function findTransactionByIdWithFilter(int $id): ?array
{
    global $transactions;

    $filteredTransactions = array_filter($transactions, function ($transaction) use ($id) {
        return $transaction["id"] === $id;
    });

    if (empty($filteredTransactions)) {
        return null;
    }

    return array_values($filteredTransactions)[0];
}

function daysSinceTransaction(string $date): int
{
    $transactionDate = new DateTime($date);
    $currentDate = new DateTime('today');

    $difference = $currentDate->diff($transactionDate);

    return $difference->days;
}

function addTransaction(int $id, string $date, float $amount, string $description, string $merchant): void
{
    global $transactions;

    $transactions[] = [
        "id" => $id,
        "date" => $date,
        "amount" => $amount,
        "description" => $description,
        "merchant" => $merchant,
    ];
}

function removeTransactionById(int $id): void
{
    global $transactions;

    foreach ($transactions as $key => $transaction) {
        if ($transaction["id"] === $id) {
            unset($transactions[$key]);
        }
    }

    $transactions = array_values($transactions);
}

/* Добавление новой транзакции */
addTransaction(8, "2026-03-06", 200.00, "Coffee", "Barista Coffee");

/* Удаление транзакции с id = 3 */
removeTransactionById(3);

/* Результаты функций */
$totalAmount = calculateTotalAmount($transactions);
$foundByDescription = findTransactionByDescription("Cinema");
$foundById = findTransactionById(2);
$foundByIdWithFilter = findTransactionByIdWithFilter(1);
$deletedTransaction = findTransactionById(3);

/* Сортировка по дате */
$transactionsByDate = $transactions;
usort($transactionsByDate, function (array $a, array $b): int {
    return strtotime($a["date"]) <=> strtotime($b["date"]);
});

/* Сортировка по сумме по убыванию */
$transactionsByAmount = $transactions;
usort($transactionsByAmount, function (array $a, array $b): int {
    return $b["amount"] <=> $a["amount"];
});

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transactions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2, h3 {
            width: 80%;
            margin: 20px auto 10px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #e6e6e6;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .results {
            width: 80%;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<h2>Original Transactions</h2>
<table>
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>Transaction Date</th>
            <th>Days Since Transaction</th>
            <th>Description</th>
            <th>Merchant</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $transaction) { ?>
            <tr>
                <td><?php echo $transaction["id"]; ?></td>
                <td><?php echo $transaction["date"]; ?></td>
                <td><?php echo daysSinceTransaction($transaction["date"]); ?></td>
                <td><?php echo $transaction["description"]; ?></td>
                <td><?php echo $transaction["merchant"]; ?></td>
                <td><?php echo $transaction["amount"]; ?></td>
            </tr>
        <?php } ?>

        <tr>
            <td colspan="5"><strong>Total</strong></td>
            <td><strong><?php echo $totalAmount; ?></strong></td>
        </tr>
    </tbody>
</table>

<h2>Sorted by Date</h2>
<table>
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>Transaction Date</th>
            <th>Description</th>
            <th>Merchant</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactionsByDate as $transaction) { ?>
            <tr>
                <td><?php echo $transaction["id"]; ?></td>
                <td><?php echo $transaction["date"]; ?></td>
                <td><?php echo $transaction["description"]; ?></td>
                <td><?php echo $transaction["merchant"]; ?></td>
                <td><?php echo $transaction["amount"]; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<h2>Sorted by Amount</h2>
<table>
    <thead>
        <tr>
            <th>Transaction ID</th>
            <th>Transaction Date</th>
            <th>Description</th>
            <th>Merchant</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactionsByAmount as $transaction) { ?>
            <tr>
                <td><?php echo $transaction["id"]; ?></td>
                <td><?php echo $transaction["date"]; ?></td>
                <td><?php echo $transaction["description"]; ?></td>
                <td><?php echo $transaction["merchant"]; ?></td>
                <td><?php echo $transaction["amount"]; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div class="results">
    <h3>Function Results</h3>

    <p>
        <strong>Search by description:</strong>
        <?php
        if ($foundByDescription !== null) {
            echo $foundByDescription["description"] . " - " . $foundByDescription["merchant"];
        } else {
            echo "Transaction not found";
        }
        ?>
    </p>

    <p>
        <strong>Search by ID using foreach:</strong>
        <?php
        if ($foundById !== null) {
            echo $foundById["description"] . " - " . $foundById["merchant"];
        } else {
            echo "Transaction not found";
        }
        ?>
    </p>

    <p>
        <strong>Search by ID using array_filter:</strong>
        <?php
        if ($foundByIdWithFilter !== null) {
            echo $foundByIdWithFilter["description"] . " - " . $foundByIdWithFilter["merchant"];
        } else {
            echo "Transaction not found";
        }
        ?>
    </p>

    <p>
        <strong>Delete transaction with ID 3:</strong>
        <?php
        if ($deletedTransaction === null) {
            echo "Transaction deleted successfully";
        } else {
            echo "Transaction was not deleted";
        }
        ?>
    </p>
</div>

</body>
</html>