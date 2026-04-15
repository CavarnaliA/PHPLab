<?php

require_once 'ValidatorInterface.php';

class StudySessionValidator implements ValidatorInterface
{
    public function validate(array $data): array
    {
        $errors = [];

        $subject = trim($data['subject'] ?? '');
        $studyDate = trim($data['study_date'] ?? '');
        $duration = trim($data['duration'] ?? '');
        $difficulty = trim($data['difficulty'] ?? '');
        $result = trim($data['result'] ?? '');
        $notes = trim($data['notes'] ?? '');

        $allowedDifficulties = ['Легко', 'Средне', 'Сложно'];
        $allowedResults = ['Выполнено', 'Частично', 'Нужно повторить'];

        if ($subject === '' || strlen($subject) < 2 || strlen($subject) > 100) {
            $errors[] = 'Название предмета должно быть от 2 до 100 символов.';
        }

        if ($studyDate === '') {
            $errors[] = 'Дата обязательна.';
        } else {
            $dateObject = DateTime::createFromFormat('Y-m-d', $studyDate);
            if (!$dateObject || $dateObject->format('Y-m-d') !== $studyDate) {
                $errors[] = 'Неверный формат даты.';
            }
        }

        if ($duration === '' || filter_var($duration, FILTER_VALIDATE_INT) === false) {
            $errors[] = 'Длительность должна быть числом.';
        } else {
            $duration = (int)$duration;
            if ($duration < 1 || $duration > 600) {
                $errors[] = 'Длительность должна быть от 1 до 600 минут.';
            }
        }

        if (!in_array($difficulty, $allowedDifficulties, true)) {
            $errors[] = 'Некорректная сложность.';
        }

        if (!in_array($result, $allowedResults, true)) {
            $errors[] = 'Некорректный результат.';
        }

        if ($notes === '' || strlen($notes) < 5 || strlen($notes) > 1000) {
            $errors[] = 'Заметки должны быть от 5 до 1000 символов.';
        }

        return $errors;
    }
}