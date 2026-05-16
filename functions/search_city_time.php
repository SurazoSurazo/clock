<?php
function searchCityTime($city_name) {
  require('config/cities.php');
  foreach ($cities as $city) {
    if ($city['name'] === $city_name) {
      $date_time = new DateTime('', new DateTimeZone($city['time_zone']));
      $time = $date_time->format('H:i');
      $date = $date_time->format('Y年m月d日');
      $city['time'] = $time;
      $city['date'] = $date;

      return $city;
    }
  }
}
