<?php

function getNounPluralForm(int $number, string $one, string $two, string $many): string
{
    $mod10 = $number % 10;

    return match (true) {
        $mod10 === 1 => $one,
        $mod10 >= 2 && $mod10 <= 4 => $two,
        default => $many,
    };
}

function normalizeDate(string $date): string
{
    $postUnix = strtotime($date);
    $interval = floor((time() - $postUnix) / 60);
    $type = '';
    $types = [
        "minutes" => ["минуту", "минуты", "минут"],
        "hours" => ["час", "часа", "часов"],
        "days" => ["день", "дня", "дней"],
        "weeks" => ["неделю", "недели", "недель"],
        "months" => ["месяц", "месяца", "месяцев"],
        "years" => ["год", "года", "лет"]
    ];

    if ($interval < 60) {
        $type = "minutes";
    }
    else if ($interval / 60 < 24) {
        $type = "hours";
        $interval = floor($interval / 60);
    } else if ($interval / 60 / 24 < 7) {
        $type = "days";
        $interval = floor($interval / 60 / 24);
    } else if ($interval / 60 / 24 / 7 < 5) {
        $type = "weeks";
        $interval = floor($interval / 60 / 24 / 7);
    } else if ($interval / 60 / 24 / 7 / 5 < 12) {
        $type = "months";
        $interval = floor($interval / 60 / 24 / 7 / 5);
    } else if ($interval / 60 / 24 / 7 / 5 >= 12) {
        $type = "years";
        $interval = floor($interval / 60 / 24 / 7 / 5 / 12);
    }

    $correctWord = getNounPluralForm($interval, $types[$type][0], $types[$type][1], $types[$type][2]);

    return "$interval $correctWord";
}