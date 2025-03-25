<?php

class Frame
{
    private array $rolls = [];
    private bool $isTenthFrame = false;

    public function __construct(bool $isTenthFrame = false)
    {
        $this->isTenthFrame = $isTenthFrame;
    }

    public function addRoll(int $pins): void
    {
        $this->rolls[] = $pins;
    }

    public function getRolls(): array
    {
        return $this->rolls;
    }

    public function getFirstRoll(): int
    {
        return $this->rolls[0] ?? 0;
    }

    public function getSecondRoll(): int
    {
        return $this->rolls[1] ?? 0;
    }

    public function isStrike(): bool
    {
        return $this->getFirstRoll() === 10;
    }

    public function isSpare(): bool
    {
        return !$this->isStrike() && ($this->getFirstRoll() + $this->getSecondRoll() === 10);
    }

    public function getTotalPins(): int
    {
        return array_sum($this->rolls);
    }
    
    public function isTenthFrame(): bool
    {
        return $this->isTenthFrame;
    }

    public function isComplete(): bool
    {
        if ($this->isTenthFrame()) {
            if (count($this->rolls) < 2) {
                return false;
            }
            if (($this->isStrike() || $this->isSpare()) && count($this->rolls) < 3) {
                return false;
            }
            return true;
        } else {
            if ($this->isStrike() && count($this->rolls) === 1) {
                return true;
            }
            return count($this->rolls) === 2;
        }
    }
}
