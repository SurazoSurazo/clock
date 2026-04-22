<?php

require_once('functions/search_city_time.php');
require_once('functions/exchange_rate.php');
require_once('functions/get_weather.php'); // ←追加
require_once('functions/get_temperature.php'); // ←気温取得

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

// ↓追加（天気取得）
$weatherTokyo = getWeather('東京');
$weatherCity = getWeather($city);

// ↓追加（気温取得）
$temperatureTokyo = getTemperature('東京');
$temperatureCity = getTemperature($city);

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
        <div class="checklist">
          <h2>チェックリスト</h2>

          <ul id="checklist-items"></ul>

          <input type="text" id="new-item" placeholder="項目を入力">
          <button onclick="addItem()">追加</button>
        </div>
      </div>

      <div class="result-cards">
        <div class="result-card">
          <div class="result-card__img-wrapper">
            <img class="result-card__img" src="img/<?php echo $tokyo['img'] ?>" alt="国旗">
          </div>
          <div class="result-card__body">
            <p class="result-card__city">
              <?php echo $tokyo['name'] ?>
            </p>
            <p class="result-card__time">
              <?php echo $tokyo['time'] ?>
            </p>
            <!-- ↓追加 -->
            <p>
              天気：<?php echo $weatherTokyo; ?>
            </p>
            <p>
              平均気温：<?php echo $temperatureTokyo; ?>
            </p>
          </div>
        </div>

        <div class="result-card">
          <div class="result-card__img-wrapper">
            <img class="result-card__img" src="img/<?php echo $comparison['img'] ?>" alt="国旗">
          </div>
          <div class="result-card__body">
            <p class="result-card__city">
              <?php echo $comparison['name'] ?>
            </p>
            <p class="result-card__time">
              <?php echo $comparison['time'] ?>
            </p>
            <!-- ↓追加 -->
            <p>
              天気：<?php echo $weatherCity; ?>
            </p>
            <p>
              平均気温：<?php echo $temperatureCity; ?>
            </p>
          </div>
        </div>
      </div>

    </div>
  </main>
  <script>
    let items = JSON.parse(localStorage.getItem('checklist') || '[]');

    function render() {
      const ul = document.getElementById('checklist-items');
      ul.innerHTML = '';

      items.forEach((item, index) => {
        const li = document.createElement('li');

        li.innerHTML = `
      <input type="checkbox" ${item.checked ? 'checked' : ''} onchange="toggle(${index})">
      ${item.text}
      <button onclick="removeItem(${index})">削除</button>
    `;

        ul.appendChild(li);
      });

      localStorage.setItem('checklist', JSON.stringify(items));
    }

    function addItem() {
      const input = document.getElementById('new-item');
      if (input.value.trim() === '') return;

      items.push({
        text: input.value,
        checked: false
      });
      input.value = '';
      render();
    }

    function toggle(index) {
      items[index].checked = !items[index].checked;
      render();
    }

    function removeItem(index) {
      items.splice(index, 1);
      render();
    }

    render();
  </script>
</body>

</html>