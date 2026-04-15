<?php

interface ValidatorInterface
{
    public function validate(array $data): array;
}