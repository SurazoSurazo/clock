<?php

require_once('functions/search_city_time.php');
require_once('functions/exchange_rate.php');

$tokyo = searchCityTime('東京');

$city = htmlspecialchars($_GET['city'], ENT_QUOTES);
$comparison = searchCityTime($city);

$currencyMap = [
  'シドニー' => 'AUD',
  '上海' => 'CNY',
  'モスクワ' => 'RUB',
  'ロンドン' => 'GBP',
  'ヨハネスブルグ' => 'ZAR',
  'ニューヨーク' => 'USD',
  'コルカタ' => 'INR',
  'サンパウロ' => 'BRL',
  'カイロ' => 'EGP',
  'リヤド' => 'SAR',
  'ブカレスト' => 'RON'
];

$currency = isset($currencyMap[$city]) ? $currencyMap[$city] : 'USD';

$rate = getRateToJpy($currency);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>World Clock</title>
  <link rel="stylesheet" href="css/sanitize.css">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/result.css">
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="/php02/index.php">
        World Clock
      </a>
    </div>
  </header>

  <main>
    <div class="result__content">

      <div class="exchange-rate">
      1<?php echo $currency; ?> = <?php echo $rate; ?> 円
      </div>

      <div class="result-cards">
        <div class="result-card">
          <div class="result-card__img-wrapper">
            <img class="result-card__img" src="img/<?php echo $tokyo['img']?>" alt="国旗">
          </div>
          <div class="result-card__body">
            <p class="result-card__city">
              <?php echo $tokyo['name']?>
            </p>
            <p class="result-card__time">
              <?php echo $tokyo['time']?>
            </p>
          </div>
        </div>
        <div class="result-card">
          <div class="result-card__img-wrapper">
            <img class="result-card__img" src="img/<?php echo $comparison['img']?>" alt="国旗">
          </div>
          <div class="result-card__body">
            <p class="result-card__city">
              <?php echo $comparison['name']?>
            </p>
            <p class="result-card__time">
              <?php echo $comparison['time']?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>

</html>