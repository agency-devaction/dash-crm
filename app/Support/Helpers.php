<?php

function obfuscate_email(?string $email = null): string
{
    if (is_null($email) || !strpos($email, '@')) {
        return '';
    }

    [$firstPart, $secondPart] = explode('@', $email);

    $qty             = (int) floor(strlen($firstPart) * 0.75);
    $remainingFirst  = strlen($firstPart) - $qty;
    $remainingSecond = strlen($secondPart) - $qty;

    $maskedFirstPart  = substr($firstPart, 0, $remainingFirst) . str_repeat('*', $qty);
    $maskedSecondPart = str_repeat('*', $qty) . substr($secondPart, $remainingSecond * -1, $remainingSecond);

    return $maskedFirstPart . '@' . $maskedSecondPart;
}
