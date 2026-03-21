<?php

declare(strict_types=1);

/**
 * Класс, описывающий одну банковскую транзакцию.
 */
class Transaction
{
    /**
     * Создает объект транзакции.
     *
     * @param int $id Уникальный идентификатор транзакции
     * @param DateTime $date Дата транзакции
     * @param float $amount Сумма транзакции
     * @param string $description Описание платежа
     * @param string $merchant Получатель платежа
     */
    public function __construct(
        private int $id,
        private DateTime $date,
        private float $amount,
        private string $description,
        private string $merchant
    ) {
    }

    /**
     * Возвращает идентификатор транзакции.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает дату транзакции.
     *
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Возвращает сумму транзакции.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Возвращает описание платежа.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Возвращает получателя платежа.
     *
     * @return string
     */
    public function getMerchant(): string
    {
        return $this->merchant;
    }

    /**
     * Возвращает количество дней с момента транзакции до текущей даты.
     *
     * @return int
     */
    public function getDaysSinceTransaction(): int
    {
        $currentDate = new DateTime();

        return (int) $this->date->diff($currentDate)->days;
    }
}

/**
 * Интерфейс для хранения транзакций.
 */
interface TransactionStorageInterface
{
    /**
     * Добавляет транзакцию в хранилище.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function addTransaction(Transaction $transaction): void;

    /**
     * Удаляет транзакцию по идентификатору.
     *
     * @param int $id
     * @return void
     */
    public function removeTransactionById(int $id): void;

    /**
     * Возвращает полный список транзакций.
     *
     * @return Transaction[]
     */
    public function getAllTransactions(): array;

    /**
     * Находит транзакцию по идентификатору.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function findById(int $id): ?Transaction;
}

/**
 * Класс для хранения транзакций и базовых операций доступа к ним.
 */
class TransactionRepository implements TransactionStorageInterface
{
    /**
     * Массив объектов Transaction.
     *
     * @var Transaction[]
     */
    private array $transactions = [];

    /**
     * Добавляет новую транзакцию.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    /**
     * Удаляет транзакцию по идентификатору.
     *
     * @param int $id
     * @return void
     */
    public function removeTransactionById(int $id): void
    {
        foreach ($this->transactions as $index => $transaction) {
            if ($transaction->getId() === $id) {
                unset($this->transactions[$index]);
                break;
            }
        }

        $this->transactions = array_values($this->transactions);
    }

    /**
     * Возвращает полный список транзакций.
     *
     * @return Transaction[]
     */
    public function getAllTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Находит транзакцию по идентификатору.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function findById(int $id): ?Transaction
    {
        foreach ($this->transactions as $transaction) {
            if ($transaction->getId() === $id) {
                return $transaction;
            }
        }

        return null;
    }
}

/**
 * Класс для выполнения бизнес-логики над транзакциями.
 */
class TransactionManager
{
    /**
     * @param TransactionStorageInterface $repository
     */
    public function __construct(
        private TransactionStorageInterface $repository
    ) {
    }

    /**
     * Вычисляет общую сумму всех транзакций.
     *
     * @return float
     */
    public function calculateTotalAmount(): float
    {
        $total = 0.0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            $total += $transaction->getAmount();
        }

        return $total;
    }

    /**
     * Вычисляет сумму транзакций за указанный период.
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public function calculateTotalAmountByDateRange(string $startDate, string $endDate): float
    {
        $total = 0.0;
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        foreach ($this->repository->getAllTransactions() as $transaction) {
            $transactionDate = $transaction->getDate();

            if ($transactionDate >= $start && $transactionDate <= $end) {
                $total += $transaction->getAmount();
            }
        }

        return $total;
    }

    /**
     * Подсчитывает количество транзакций по определенному получателю.
     *
     * @param string $merchant
     * @return int
     */
    public function countTransactionsByMerchant(string $merchant): int
    {
        $count = 0;

        foreach ($this->repository->getAllTransactions() as $transaction) {
            if ($transaction->getMerchant() === $merchant) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Сортирует транзакции по дате.
     *
     * @return Transaction[]
     */
    public function sortTransactionsByDate(): array
    {
        $transactions = $this->repository->getAllTransactions();

        usort($transactions, function (Transaction $a, Transaction $b): int {
            return $a->getDate()->getTimestamp() <=> $b->getDate()->getTimestamp();
        });

        return $transactions;
    }

    /**
     * Сортирует транзакции по сумме по убыванию.
     *
     * @return Transaction[]
     */
    public function sortTransactionsByAmountDesc(): array
    {
        $transactions = $this->repository->getAllTransactions();

        usort($transactions, function (Transaction $a, Transaction $b): int {
            return $b->getAmount() <=> $a->getAmount();
        });

        return $transactions;
    }
}

/**
 * Класс для вывода транзакций в виде HTML-таблицы.
 */
final class TransactionTableRenderer
{
    /**
     * Формирует HTML-таблицу со списком транзакций.
     *
     * @param Transaction[] $transactions
     * @return string
     */
    public function render(array $transactions): string
    {
        $html = '<table border="1" cellpadding="8" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<th>ID транзакции</th>';
        $html .= '<th>Дата</th>';
        $html .= '<th>Сумма</th>';
        $html .= '<th>Описание</th>';
        $html .= '<th>Название получателя</th>';
        $html .= '<th>Категория получателя</th>';
        $html .= '<th>Количество дней с момента транзакции</th>';
        $html .= '</tr>';

        foreach ($transactions as $transaction) {
            $html .= '<tr>';
            $html .= '<td>' . $transaction->getId() . '</td>';
            $html .= '<td>' . $transaction->getDate()->format('Y-m-d') . '</td>';
            $html .= '<td>' . $transaction->getAmount() . '</td>';
            $html .= '<td>' . $transaction->getDescription() . '</td>';
            $html .= '<td>' . $transaction->getMerchant() . '</td>';
            $html .= '<td>' . $this->getMerchantCategory($transaction->getMerchant()) . '</td>';
            $html .= '<td>' . $transaction->getDaysSinceTransaction() . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        return $html;
    }

    /**
     * Определяет категорию получателя по его названию.
     *
     * @param string $merchant
     * @return string
     */
    private function getMerchantCategory(string $merchant): string
    {
        return match ($merchant) {
            'Linella', 'Kaufland' => 'Супермаркет',
            'Orange', 'Moldcell' => 'Связь',
            'Aliexpress', 'Ultra' => 'Онлайн-магазин',
            'Lukoil' => 'Топливо',
            'Premier Energy', 'Apa-Canal Chisinau' => 'Коммунальные услуги',
            'Dita Hyper Pharmacy' => 'Аптека',
            'Andy\'s Pizza' => 'Ресторан',
            default => 'Другое',
        };
    }
}

$repository = new TransactionRepository();

$transaction1 = new Transaction(1, new DateTime('2026-03-01'), 250.50, 'Покупка продуктов', 'Linella');
$transaction2 = new Transaction(2, new DateTime('2026-03-02'), 120.00, 'Оплата мобильной связи', 'Moldcell');
$transaction3 = new Transaction(3, new DateTime('2026-03-03'), 980.00, 'Оплата электричества', 'Premier Energy');
$transaction4 = new Transaction(4, new DateTime('2026-03-04'), 75.90, 'Покупка лекарства', 'Dita Hyper Pharmacy');
$transaction5 = new Transaction(5, new DateTime('2026-03-05'), 430.00, 'Заправка автомобиля', 'Lukoil');
$transaction6 = new Transaction(6, new DateTime('2026-03-06'), 210.30, 'Покупка одежды', 'Aliexpress');
$transaction7 = new Transaction(7, new DateTime('2026-03-07'), 150.00, 'Ужин в ресторане', 'Andy\'s Pizza');
$transaction8 = new Transaction(8, new DateTime('2026-03-08'), 560.40, 'Покупка техники', 'Ultra');
$transaction9 = new Transaction(9, new DateTime('2026-03-09'), 310.20, 'Оплата воды', 'Apa-Canal Chisinau');
$transaction10 = new Transaction(10, new DateTime('2026-03-10'), 95.70, 'Покупка продуктов', 'Kaufland');

$repository->addTransaction($transaction1);
$repository->addTransaction($transaction2);
$repository->addTransaction($transaction3);
$repository->addTransaction($transaction4);
$repository->addTransaction($transaction5);
$repository->addTransaction($transaction6);
$repository->addTransaction($transaction7);
$repository->addTransaction($transaction8);
$repository->addTransaction($transaction9);
$repository->addTransaction($transaction10);

$renderer = new TransactionTableRenderer();

echo $renderer->render($repository->getAllTransactions());