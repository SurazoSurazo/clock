# World Clock

## 概要

World Clock は、日本と世界各国の都市情報を比較表示するWebアプリケーションです。

都市を選択すると、日本（東京）と対象都市について以下の情報を表示します。

- 現在日時
- 現在時刻
- 為替レート（対象国通貨 → 円）
- 天気情報
- 気温情報

さらに旅行を想定した機能として、都市ごとに管理できるチェックリスト機能を実装しています。

- 持ち物リスト
- 観光名所リスト
- 食べたいものリスト

チェックリストはブラウザの LocalStorage を利用し、都市ごとに保存されます。

---

## 使用技術

### フロントエンド

- HTML
- CSS
- JavaScript

### バックエンド

- PHP

### API

- OpenWeather API
    - 天気情報取得
    - 気温情報取得

- ExchangeRate API
    - 為替レート取得

### データ保存

- LocalStorage

---

## 機能一覧

### 1. 世界時間比較機能

選択した都市と東京の日時・時刻を比較表示します。

表示項目

- 都市名
- 日付
- 時刻

対象都市

- シドニー
- 上海
- モスクワ
- ロンドン
- ヨハネスブルグ
- ニューヨーク
- コルカタ
- サンパウロ
- カイロ
- リヤド
- ブカレスト

---

### 2. 為替レート表示機能

選択した都市の通貨を基準に、円換算レートを取得して表示します。

表示例

```text
1USD = 156.24円
```

---

### 3. 天気表示機能

OpenWeather API を利用し、対象都市の現在の天気を取得します。

表示例

```text
天気：晴れ
```

---

### 4. 気温表示機能

OpenWeather API を利用し、対象都市の現在気温を取得します。

表示例

```text
平均気温：25.3°C
```

---

### 5. 旅行チェックリスト機能

旅行準備用のリストを作成できます。

実装内容

- 項目追加
- チェックON/OFF
- 個別削除
- 一括削除
- Enterキー追加対応
- 都市ごとの保存

対象リスト

- 持ち物リスト
- 観光名所
- 食べたいものリスト

---

## ディレクトリ構成

```bash
php02/
├── config/
│   └── cities.php
│
├── css/
│   ├── common.css
│   ├── index.css
│   ├── result.css
│   └── sanitize.css
│
├── functions/
│   ├── exchange_rate.php
│   ├── get_temperature.php
│   ├── get_weather.php
│   └── search_city_time.php
│
├── img/
│   ├── america.jpg
│   ├── australia.jpg
│   ├── brasil.jpg
│   ├── british.jpg
│   ├── china.jpg
│   ├── egypt.jpg
│   ├── india.jpg
│   ├── japan.jpg
│   ├── romania.jpg
│   ├── russia.jpg
│   ├── saudi_arabia.jpg
│   └── south-africa.jpg
│
├── index.php
├── result.php
└── README.md
```

---

## セットアップ方法

### 1. リポジトリをクローン

```bash
git clone [リポジトリURL]
```

### 2. プロジェクトへ移動

```bash
cd php02
```

### 3. ローカルサーバー起動

PHP組み込みサーバーを利用する場合

```bash
php -S localhost:8000
```

### 4. ブラウザでアクセス

```text
http://localhost:8000
```

---

## API設定

### OpenWeather API

以下の箇所にAPIキーを設定しています。

```php
$apiKey = 'YOUR_API_KEY';
```

対象ファイル

```text
functions/get_weather.php
functions/get_temperature.php
```

### ExchangeRate API

為替レート取得API

```php
https://api.exchangerate-api.com/v4/latest/
```

---

## 工夫した点

### 都市ごとのチェックリスト管理

LocalStorage のキーに都市名を含めることで、都市別に異なるチェックリストを保持できるようにしました。

例

```javascript
checklist-シドニー-packing
checklist-ロンドン-food
```

### APIレスポンス失敗時の処理

APIデータが取得できなかった場合はエラー表示ではなく以下を返すようにしました。

```text
取得失敗
```

### URLエンコード対応

都市名に空白が含まれるケース（New York、Sao Pauloなど）に対応するため、`urlencode()` を使用しています。

```php
$cityEnEncoded = urlencode($cityEn);
```

---

## 今後改善したい点

- APIキーの環境変数化
- API通信エラー時の詳細表示
- レスポンシブ対応強化
- チェックリストのDB保存
- 検索可能都市の追加

---

## 作成者

Daichi Iwama

---

## 更新履歴

- 2026/05/16
    - README初版作成