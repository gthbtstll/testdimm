<?php
error_reporting(E_ERROR);
const APIKEY = "";
$url = "https://dimm.retailcrm.ru/api/v5/orders?apiKey=" . APIKEY;
$itemQuantityArr = array();
$itemPriceArr = array();
$page = 1;

function getOrders($url, $page)
{
    $content = file_get_contents($url . "&page=" . $page);
    $jsonResults = json_decode($content);
    if (count($jsonResults->orders)) return $jsonResults->orders;
    else return false;
}

while ($orders = getOrders($url, $page)) {
    $page++;
    foreach ($orders as $order) {
        foreach ($order->items as $item) {
            $offerName = $item->offer->name;
            $itemQuantityArr[$offerName] += $item->quantity;
            $itemPriceArr[$offerName] += $item->initialPrice * $item->quantity;
        }
    }
}

if (!empty($itemQuantityArr) && !empty($itemPriceArr)) {
    $maxSum = max($itemPriceArr);
    $maxSumItems = array_keys($itemPriceArr, $maxSum);
    $maxQuantity = max($itemQuantityArr);
    $maxQuantityItems = array_keys($itemQuantityArr, $maxQuantity);

    foreach ($maxSumItems as $maxSumItem) {
        echo "<br>Топ товар по сумме в заказах: " . $maxSumItem . " - " . $maxSum;
    }
    foreach ($maxQuantityItems as $maxQuantityItem) {
        echo "<br>Топ товар по количеству в заказах: " . $maxQuantityItem . " - " . $maxQuantity;
    }
}
