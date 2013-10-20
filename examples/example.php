<?php
use LuckyBox\LuckyBox;
use LuckyBox\Card\IdCard;

$loader = require_once __DIR__ . "/../vendor/autoload.php";
$loader->add('LuckyBox\\', __DIR__);

// Items
$items = array(
    1 => array('name' => 'Coin',     'rate' => 60), // 60%
    2 => array('name' => 'Mushroom', 'rate' => 35), // 35%
    3 => array('name' => 'Star',     'rate' => 5),  //  5%
);

$luckyBox = new LuckyBox();

// Adds cards into LuckyBox.
foreach ($items as $id => $item) {
    $card = new IdCard();
    $card->setId($id)
         ->setRate($item['rate']);
    $luckyBox->add($card);
}

$result = array(
    1 => 0,
    2 => 0,
    3 => 0
);

// Draws the card a hundred times and count up the result.
for ($i = 0; $i < 100; $i++) {
    $card = $luckyBox->draw();
    $result[$card->getId()]++;
}

// Output the result which must be close to the rate of items.
foreach ($result as $id => $count) {
    echo $items[$id]['name'] . ': ' . $count . PHP_EOL;
}
