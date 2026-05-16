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
            <p>
              日付：<?php echo $tokyo['date'] ?>
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
            <p>
              日付：<?php echo $comparison['date'] ?>
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

      <div class="checklists">
        <div class="checklist" data-list-key="packing">
          <h2>持ち物リスト</h2>

          <ul class="checklist-items"></ul>

          <input type="text" class="new-item" placeholder="項目を入力">
          <div class="checklist__actions">
            <button onclick="addItem('packing')">追加</button>
            <button onclick="deleteAllItems('packing')">まとめて削除</button>
          </div>
        </div>

        <div class="checklist" data-list-key="sightseeing">
          <h2>観光名所</h2>

          <ul class="checklist-items"></ul>

          <input type="text" class="new-item" placeholder="項目を入力">
          <div class="checklist__actions">
            <button onclick="addItem('sightseeing')">追加</button>
            <button onclick="deleteAllItems('sightseeing')">まとめて削除</button>
          </div>
        </div>

        <div class="checklist" data-list-key="food">
          <h2>食べたいものリスト</h2>

          <ul class="checklist-items"></ul>

          <input type="text" class="new-item" placeholder="項目を入力">
          <div class="checklist__actions">
            <button onclick="addItem('food')">追加</button>
            <button onclick="deleteAllItems('food')">まとめて削除</button>
          </div>
        </div>
      </div>

    </div>
  </main>
  <script>
    const selectedCity = <?php echo json_encode($city, JSON_UNESCAPED_UNICODE); ?>;
    const listKeys = ['packing', 'sightseeing', 'food'];
    const items = {};

    function getStorageKey(listKey) {
      return `checklist-${selectedCity}-${listKey}`;
    }

    listKeys.forEach((listKey) => {
      items[listKey] = JSON.parse(localStorage.getItem(getStorageKey(listKey)) || '[]');
    });

    function render(listKey) {
      const checklist = document.querySelector(`[data-list-key="${listKey}"]`);
      const ul = checklist.querySelector('.checklist-items');
      ul.innerHTML = '';

      items[listKey].forEach((item, index) => {
        const li = document.createElement('li');
        li.addEventListener('click', function() {
          toggle(listKey, index);
        });

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.checked = item.checked;
        checkbox.addEventListener('click', function(event) {
          event.stopPropagation();
          toggle(listKey, index);
        });

        const text = document.createElement('span');
        text.textContent = item.text;

        const deleteButton = document.createElement('button');
        deleteButton.textContent = '削除';
        deleteButton.addEventListener('click', function(event) {
          event.stopPropagation();
          removeItem(listKey, index);
        });

        li.appendChild(checkbox);
        li.appendChild(text);
        li.appendChild(deleteButton);

        ul.appendChild(li);
      });

      localStorage.setItem(getStorageKey(listKey), JSON.stringify(items[listKey]));
    }

    function addItem(listKey) {
      const checklist = document.querySelector(`[data-list-key="${listKey}"]`);
      const input = checklist.querySelector('.new-item');
      if (input.value.trim() === '') return;

      items[listKey].push({
        text: input.value,
        checked: false
      });
      input.value = '';
      render(listKey);
    }

    function toggle(listKey, index) {
      items[listKey][index].checked = !items[listKey][index].checked;
      render(listKey);
    }

    function removeItem(listKey, index) {
      items[listKey].splice(index, 1);
      render(listKey);
    }

    function deleteAllItems(listKey) {
      items[listKey] = [];
      localStorage.setItem(getStorageKey(listKey), JSON.stringify(items[listKey]));
      render(listKey);
    }

    document.querySelectorAll('.checklist').forEach((checklist) => {
      const listKey = checklist.dataset.listKey;
      const input = checklist.querySelector('.new-item');

      input.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && !event.isComposing) {
          addItem(listKey);
        }
      });
    });

    listKeys.forEach(render);
  </script>
</body>

</html>
