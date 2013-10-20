LuckyBox
=========

LuckyBox is a library for PHP to pick up the one with a specified probability from some items.

Requirements
------------

- PHP 5.3.0 and up

Install
-------

In your application or dependent library root directory, create a composer.json file.
In the require or alternatively in the require-dev section, add the following dependency:

    "cloned/luckybox": "$VERSION"

with $VERSION being one of the versions available at [Packagist](https://packagist.org/packages/cloned/luckybox "Packagist").

Usage
-----

### Example

Here is a simple example to pick up the item from 3 items which are Coin, Mushroom and Star.
Probability of each item is below.

* Coin: 60%
* Mushroom: 35%
* Star: 5%

```php
<?php
use LuckyBox\LuckyBox;
use LuckyBox\Card\IdCard;

// Items
$items = array(
    1 => array('name' => 'Coin',     'rate' => 60), // 60%
    2 => array('name' => 'Mushroom', 'rate' => 35), // 35%
    3 => array('name' => 'Star',     'rate' => 5),  //  5%
);

// Setup
$luckyBox = new LuckyBox();

foreach ($items as $id => $item) {
    $card = new IdCard();
    $card->setId($id)
         ->setRate($item['rate']);

    $luckyBox->add($card);
}

// Draw
$card = $luckyBox->draw();
$item = $items[$card->getId()];

echo "You got {$item['name']}" . PHP_EOL;
```

### Consume cards

Cards in LuckyBox are not consumable by default. This means that the cards can be drawn endlessly.
If you want to consume the cards in LuckyBox, set consumable to true.

```php
$luckyBox = new LuckyBox();

// Add some cards.

$luckyBox->setConsumable(true);

while (!$luckyBox->isEmpty()) {
    $card = $luckyBox->draw();

    // Do something.
}
```

### Increase the accuracy

You may want to higher accuracy than percent (0 to 100). You can set the rate more than 100.

```php
$card1 = new IdCard();
$card2 = new IdCard();
$card1->setRate(1023); // 10.23%
$card2->setRate(8977); // 89.77%
```

### Remove a card

* Remove one card

```php
$luckyBox->remove($card);
```

* Remove all of the cards

```php
$luckyBox->clear();
```

License
-------

LuckyBox is licensed under the MIT License - see the `LICENSE` file for details
