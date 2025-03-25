<?php

require_once 'Game.php';

$game = new Game();

while (!$game->isGameOver()) {
    echo "========================================\n";
    echo "Current frame: " . $game->getCurrentFrameNumber() . " out of 10\n";
    echo "Enter the number of pins knocked down (0-10): ";

    $input = trim(fgets(STDIN));

    if (!is_numeric($input)) {
        echo "Invalid input. Please try again.\n";
        continue;
    }

    $pins = (int)$input;

    if ($pins < 0 || $pins > 10) {
        echo "Invalid input. Please try again.\n";
        continue;
    }

    $game->roll($pins);

    echo "Current score: " . $game->getScore() . "\n";
}

echo "========================================\n";
echo "Final score: " . $game->getScore() . "\n";

$framesScore = $game->getFramesScore();

foreach ($framesScore as $frameNumber => $frameScore) {
    echo "Frame {$frameNumber}: {$frameScore}\n";
}
