<?php

namespace LuckyBox\Card;

use LuckyBox\Card\Card;

class IdCard implements Card
{

    private $id;

    private $rate;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

}
