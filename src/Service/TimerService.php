<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 13:34
 */

namespace App\Service;


use App\Entity\Timer;

class TimerService
{
    private $timer;

    public function __construct()
    {
        $this->timer = new Timer();
    }

    public function startTimer()
    {
        $this->timer->setStart(time());
    }

    public function stopTimer()
    {
        $this->timer->setEnd(time());

        $this
            ->timer
            ->setDifference($this->timer->getEnd() - $this->timer->getStart());

        $this
            ->timer
            ->setDuration($this->timer->getDuration() + $this->timer->getDifference());
    }

    public function getTimerDuration(){
        return $this->timer->getDuration();
    }
}