<?php

include __DIR__ . '/../../bootstrap.php';

$winningCombos = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6]
];

if (isset($_POST)) {
    $cells = json_decode($_POST['cells'], true);
    $data = [];
    //verify at least 3 cells
    $players = getCombos($cells);
    if (canWin($players) === true) {
        //See if we have a winner
        $data['winner'] = getWinner($players, $winningCombos);
    }
    if (isset($data['winner']) === false || is_null($data['winner']) === true) {
        //Computer Play
        $compTurn = computerTurn($cells);
        if (empty($compTurn) === true) {
            #nobody won
            $data['tie'] = true;
        } else {
            $data['compTurn'] = $compTurn;
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);

}

function canWin(array $players): bool
{
    return count(array_filter($players, function ($p) {
        return count($p) > 2;
    })) > 0;
}

function getWinner(array $players, array $winningCombos): ?string
{
    foreach ($winningCombos as $combo) {
        //all 3 match we a winner
        $x = array_intersect($players['X'], $combo);
        $o = array_intersect($players['O'], $combo);
        if (count($o) === 3) {
            return 'O';
        }
        if (count($x) === 3) {
            return 'X';
        }
    }

    return null;
}

function isAWinner(&$winner, array $cells): bool
{

    //straight across
    if ($cells['cell0'] === $cells['cell0'] && $cells['cell1'] === $cells['cell2']) {
        $winner = $cells['cell0'];
        $return = true;
    }
    if ($cells['cell3'] === $cells['cell4'] && $cells['cell4'] === $cells['cell5']) {
        $winner = $cells['cell3'];
        $return = true;
    }
    if ($cells['cell6'] === $cells['cell7'] && $cells['cell7'] === $cells['cell8']) {
        $winner = $cells['cell6'];
        $return = true;
    }
    //Verticles
    if ($cells['cell0'] === $cells['cell3'] && $cells['cell3'] === $cells['cell7']) {
        $winner = $cells['cell0'];
        $return = true;
    }
    if ($cells['cell1'] === $cells['cell4'] && $cells['cell4'] === $cells['cell8']) {
        $winner = $cells['cell2'];
        $return = true;
    }
    if ($cells['cell2'] === $cells['cell5'] && $cells['cell5'] === $cells['cell9']) {
        $winner = $cells['cell2'];
        $return = true;
    }
    //Diags
    if ($cells['cell0'] === $cells['cell4'] && $cells['cell4'] === $cells['cell9']) {
        $winner = $cells['cell0'];
        $return = true;
    }
    if ($cells['cell2'] === $cells['cell4'] && $cells['cell4'] === $cells['cell7']) {
        $winner = $cells['cell0'];
        $return = true;
    }


    return $return;
}
function computerTurn($cells): ?int
{
    $spots = getEmptySpots($cells);
    return empty($spots) === false ? $spots[array_rand($spots)] : null;
}

function getEmptySpots(array $cells): ?array
{
    return array_values(array_keys(array_filter($cells, 'is_numeric')));
}

function getWinnerSpace(array $winningCombos, array $player): array
{
    $combos = array_values($player);
    dd(array_intersect($combos, $winningCombos));
    return [];
}

function getCombos($cells): ?array
{
    $x = array_keys(array_filter($cells, function ($x) {
        return $x === 'X';
    }));
    $o = array_keys(array_filter($cells, function ($x) {
        return $x === 'O';
    }));

    return ['X' => $x, 'O' => $o];
}

function compPlayerMove(array $cells): string
{
    $available = getEmptySpots($cells);
    $pick = array_rand($available);

    return $available[$pick];
}