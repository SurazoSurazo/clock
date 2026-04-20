<?php
function getRateToJpy($baseCurrency) {
  $url = "https://api.exchangerate-api.com/v4/latest/" . $baseCurrency;
  $response = file_get_contents($url);
  $data = json_decode($response, true);

  return $data['rates']['JPY'];
}