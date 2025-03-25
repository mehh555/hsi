<?php

require_once 'Game.php';

$game = new Game();

while (!$game->isGameOver()) {
    echo "========================================\n";
    echo "Aktualna runda: " . $game->getCurrentFrameNumber() . " z 10\n";
    echo "Podaj liczbę zbitych kręgli (0-10): ";

    $input = trim(fgets(STDIN));

    if (!is_numeric($input)) {
        echo "Błędna wartość. Spróbuj jeszcze raz.\n";
        continue;
    }

    $pins = (int)$input;

    if ($pins < 0 || $pins > 10) {
        echo "Błędna wartość. Spróbuj jeszcze raz.\n";
        continue;
    }

    $game->roll($pins);

    echo "Aktualny wynik: " . $game->getScore() . "\n";
}

echo "========================================\n";
echo "=== Gra zakończona! ===\n";
echo "Wynik końcowy: " . $game->getScore() . "\n";

$framesScore = $game->getFramesScore();

foreach ($framesScore as $frameNumber => $frameScore) {
    echo "Ramka {$frameNumber}: {$frameScore}\n";
}
