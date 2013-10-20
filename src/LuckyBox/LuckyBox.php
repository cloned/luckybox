<?php

namespace LuckyBox;

use LuckyBox\Card\Card;

/**
 * LuckyBox
 */
class LuckyBox
{

    private $cards = array();

    private $totalRate = 0;

    private $consumable = false;

    /**
     * Returns the card with the specified rate of cards, or null if LuckyBox contains no cards.
     *
     * @return Card
     */
    public function draw()
    {
        $position = $this->getRandomPosition();

        if ($position === null) {
            return null;
        }

        $card = $this->find($position);

        if ($card !== null && $this->consumable) {
            $this->remove($card);
        }

        return $card;
    }

    /**
     * Adds the specified card to this LuckyBox.
     *
     * @param Card $card
     */
    public function add(Card $card)
    {
        $this->cards[] = $card;
        $this->totalRate += $card->getRate();
    }

    /**
     * Removes the specified card from this LuckyBox if it is present.
     *
     * @param Card $card
     */
    public function remove(Card $card)
    {
        $this->cards = array_values(array_filter($this->cards, function($value) use ($card) {
            return $card !== $value;
        }));
        $this->totalRate -= $card->getRate();
    }

    /**
     * Removes all of the cards from this LuckyBox.
     */
    public function clear()
    {
        $this->cards = array();
        $this->totalRate = 0;
    }

    /**
     * Returns true if this LuckyBox contains no cards.
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->cards) === 0;
    }

    /**
     * @param bool $consumable
     */
    public function setConsumable($consumable)
    {
        $this->consumable = $consumable;
    }

    /**
     * @return bool
     */
    public function isConsumable()
    {
        return $this->consumable;
    }

    /**
     * @param integer $position
     */
    protected function find($position)
    {
        $current = 0;

        foreach ($this->cards as $card) {
            $current += $card->getRate();

            if ($position < $current) {
                return $card;
            }
        }

        return null;
    }

    /**
     * @return integer
     */
    protected function getRandomPosition()
    {
        if ($this->totalRate < 1) {
            return null;
        } else {
            return mt_rand(0, $this->totalRate - 1);
        }
    }

}
