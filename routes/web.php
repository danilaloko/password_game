<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/generate', function () {
    return view('generate');
})->name('generate');

Route::post('/generate', function (Request $request) {
    $gridSize = $request->input('gridSize');
    $patternLength = $request->input('patternLength');
    $allowReconnect = $request->boolean('allowReconnect');
    $allowDiagonal = $request->boolean('allowDiagonal');
    
    // Сохраняем значения в сессию
    $request->session()->put('gridSize', $gridSize);
    $request->session()->put('patternLength', $patternLength);
    $request->session()->put('allowReconnect', $allowReconnect);
    $request->session()->put('allowDiagonal', $allowDiagonal);
    
    // Создаем сетку нужного размера
    $squareNumber = $gridSize * $gridSize;
    
    $pattern = generatePattern($squareNumber, $gridSize);
    return view('generate', [
        'pattern' => $pattern,
        'allowReconnect' => $allowReconnect,
        'allowDiagonal' => $allowDiagonal
    ]);
})->name('generate');

function generatePattern($totalDots, $gridSize) {
    $grid = [];
    $dots = [];
    
    // Создаем квадратную сетку
    for ($y = 0; $y < $gridSize; $y++) {
        $grid[$y] = [];
        for ($x = 0; $x < $gridSize; $x++) {
            $grid[$y][$x] = [
                'x' => $x,
                'y' => $y,
                'connected' => true // Теперь все точки connected
            ];
            $dots[] = ['x' => $x, 'y' => $y];
        }
    }
    
    // Соединяем точки
    for ($i = 0; $i < count($dots); $i++) {
        $current = $dots[$i];
        
        if ($i > 0) {
            $prev = $dots[$i - 1];
            $grid[$current['y']][$current['x']]['prevX'] = $prev['x'];
            $grid[$current['y']][$current['x']]['prevY'] = $prev['y'];
        }
    }
    
    return [
        'grid' => $grid,
        'dots' => $dots,
        'totalDots' => $totalDots
    ];
}
