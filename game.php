<?php

require_once 'Frame.php';

class Game
{
    private array $frames = [];
    private int $currentFrameIndex = 0;
    private bool $gameOver = false;

    public function __construct()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->frames[$i] = new Frame($i === 9);
        }
    }

    public function roll(int $pins): void
    {
        $currentFrame = $this->frames[$this->currentFrameIndex];
        if (!$this->validateRoll($currentFrame, $pins)) {
            echo "W jednej rundzie możesz łącznie zbić maksymalnie 10 kręgli.\n";
            return;
        }
        $currentFrame->addRoll($pins);
        if ($currentFrame->isComplete()) {
            if ($this->currentFrameIndex < 9) {
                $this->currentFrameIndex++;
            } else {
                $this->gameOver = true;
            }
        }
    }

    private function validateRoll(Frame $frame, int $pins): bool
    {
        if ($pins < 0 || $pins > 10) {
            return false;
        }
        if ($frame->isComplete()) {
            return false;
        }
        if (!$frame->isTenthFrame()) {
            $currentSum = $frame->getTotalPins();
            if ($currentSum + $pins > 10) {
                return false;
            }
        } else {
            $rollsSoFar = $frame->getRolls();
            $countSoFar = count($rollsSoFar);
            if ($countSoFar === 0) {
                return true;
            } else if ($countSoFar === 1) {
                $firstRoll = $rollsSoFar[0];
                if ($firstRoll < 10 && ($firstRoll + $pins > 10)) {
                    return false;
                }
            } else {
                $firstRoll = $rollsSoFar[0];
                $secondRoll = $rollsSoFar[1];
                $sumFirstTwo = $firstRoll + $secondRoll;
                if ($firstRoll < 10 && $sumFirstTwo < 10) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getScore(): int
    {
        $score = 0;
        for ($i = 0; $i < 10; $i++) {
            $frame = $this->frames[$i];
            $frameScore = $frame->getTotalPins();
            if ($frame->isStrike()) {
                $frameScore += $this->getStrikeBonus($i);
            } elseif ($frame->isSpare()) {
                $frameScore += $this->getSpareBonus($i);
            }
            $score += $frameScore;
        }
        return $score;
    }

    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    public function getFramesScore(): array
    {
        $scores = [];
        $cumulativeScore = 0;
        for ($i = 0; $i < 10; $i++) {
            $frameScore = $this->calculateFrameScore($i);
            $cumulativeScore += $frameScore;
            $scores[$i + 1] = $cumulativeScore;
        }
        return $scores;
    }

    private function calculateFrameScore(int $index): int
    {
        $frame = $this->frames[$index];
        $frameScore = $frame->getTotalPins();
        if ($frame->isStrike()) {
            $frameScore += $this->getStrikeBonus($index);
        } elseif ($frame->isSpare()) {
            $frameScore += $this->getSpareBonus($index);
        }
        return $frameScore;
    }

    private function getStrikeBonus(int $frameIndex): int
    {
        $bonus = 0;
        $rollsCount = 0;
        for ($i = $frameIndex + 1; $i < count($this->frames); $i++) {
            foreach ($this->frames[$i]->getRolls() as $roll) {
                $bonus += $roll;
                $rollsCount++;
                if ($rollsCount === 2) {
                    return $bonus;
                }
            }
        }
        return $bonus;
    }

    private function getSpareBonus(int $frameIndex): int
    {
        if ($frameIndex + 1 < count($this->frames)) {
            $nextFrame = $this->frames[$frameIndex + 1];
            return $nextFrame->getFirstRoll();
        }
        return 0;
    }

    public function getCurrentFrameNumber(): int
    {
        return $this->currentFrameIndex + 1;
    }
}
