<?php

function getWeather($city)
{
    $cityMap = [
        '東京' => 'Tokyo',
        'シドニー' => 'Sydney',
        '上海' => 'Shanghai',
        'モスクワ' => 'Moscow',
        'ロンドン' => 'London',
        'ヨハネスブルグ' => 'Johannesburg',
        'ニューヨーク' => 'New York',
        'コルカタ' => 'Kolkata',
        'サンパウロ' => 'Sao Paulo',
        'カイロ' => 'Cairo',
        'リヤド' => 'Riyadh',
        'ブカレスト' => 'Bucharest'
    ];

    $apiKey = 'a1d4d8ae416ac0089634b7181e21c3fa';

    $cityEn = isset($cityMap[$city]) ? $cityMap[$city] : 'Tokyo';

// ↓この1行を追加
    $cityEnEncoded = urlencode($cityEn);

// ↓この行を書き換え
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$cityEnEncoded}&appid={$apiKey}&units=metric&lang=ja";

    $json = file_get_contents($url);
    $data = json_decode($json, true);

    if (!$data || !isset($data['weather'][0]['description'])) {
        return '取得失敗';
    }

    return $data['weather'][0]['description'];
}