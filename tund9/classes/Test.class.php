<?php


class Test
{
    private $secretNumber;
    public $knownNumber;

    function __construct($sentNumber){
        $this->secretNubmer = 3;
        $this->knownNumber = $sentNumber;
        echo "Salajane: " .$this->secretNumber ." ja teadaolev: " .$this->knownNumber;
        $this->addNumbers();
    }

    function __destruct()
    {

    }

    private function addNumbers(){
        $sum = $this->secretNubmer + $this->knownNumber;
        echo "Summa on: ".$sum;
    }

    public function multiplyNumbers(){
        $sum = $this->secretNubmer * $this->knownNumber;
        echo "Korrutis on: ".$sum;
    }

}