<!DOCTYPE html>
<html>
<head>
    <title>Генератор</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }

        h1 {
            color: #2d3748;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            text-align: left;
        }

        label {
            color: #4a5568;
            font-weight: 500;
        }

        input[type="number"] {
            padding: 0.75rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button:hover {
            background: #5a67d8;
        }

        .pattern-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
            position: relative;
            display: inline-block;
        }

        .row {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .dot {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #cbd5e0;
            margin: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .dot-label {
            color: white;
            font-weight: bold;
            font-size: 14px;
            user-select: none;
            pointer-events: none;
        }

        .dot:hover {
            transform: scale(1.1);
        }

        .selected {
            background-color: #667eea;
            box-shadow: 0 0 0 2px #5a67d8;
        }

        .line {
            position: absolute;
            background-color: #667eea;
            height: 3px;
            transform-origin: left center;
            pointer-events: none;
            opacity: 0.6;
        }

        #clearButton {
            background: #e53e3e;
            margin-top: 1rem;
        }

        #clearButton:hover {
            background: #c53030;
        }

        @media (max-width: 640px) {
            .container {
                padding: 1.5rem;
                width: 95%;
            }

            h1 {
                font-size: 1.5rem;
            }

            .dot {
                width: 20px;
                height: 20px;
                margin: 6px;
            }
        }

        .result-info {
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            text-align: center;
        }

        .pattern-result {
            margin-top: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .pattern-result.correct {
            color: #48bb78;
        }

        .pattern-result.incorrect {
            color: #e53e3e;
        }

        .result-info p {
            margin: 0.5rem 0;
            color: #2d3748;
        }

        .solution-container {
            margin-top: 2rem;
            text-align: center;
        }

        .show-solution-btn {
            background-color: #4a5568;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-bottom: 1rem;
        }

        .show-solution-btn:hover {
            background-color: #2d3748;
        }

        .solution-pattern {
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .solution-pattern.visible {
            opacity: 1;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Генератор графического пароля</h1>
        <form method="POST" action="{{ route('generate') }}">
            @csrf
            <div class="form-group">
                <label for="gridSize">Размер сетки (количество точек):</label>
                <input type="number" 
                       id="gridSize" 
                       name="gridSize" 
                       required 
                       min="3" 
                       value="{{ old('gridSize', session('gridSize', 3)) }}">
            </div>
            <div class="form-group">
                <label for="patternLength">Длина пароля (минимум 3):</label>
                <input type="number" 
                       id="patternLength" 
                       name="patternLength" 
                       required 
                       min="3"
                       value="{{ old('patternLength', session('patternLength', 3)) }}">
            </div>
            <div class="checkbox-group">
                <label for="allowReconnect">
                    <input type="checkbox" 
                           id="allowReconnect" 
                           name="allowReconnect"
                           {{ old('allowReconnect', session('allowReconnect')) ? 'checked' : '' }}>
                    Разрешить повторное соединение с точками
                </label>
            </div>
            <div class="checkbox-group">
                <label for="allowDiagonal">
                    <input type="checkbox" 
                           id="allowDiagonal" 
                           name="allowDiagonal"
                           {{ old('allowDiagonal', session('allowDiagonal')) ? 'checked' : '' }}>
                    Разрешить диагональные соединения
                </label>
            </div>
            <button type="submit">Сгенерировать</button>
        </form>

        @if(isset($pattern))
        <div class="pattern-container" id="patternContainer" 
             data-allow-reconnect="{{ $allowReconnect ? 'true' : 'false' }}"
             data-allow-diagonal="{{ $allowDiagonal ? 'true' : 'false' }}"
             data-pattern-length="{{ session('patternLength') }}">
            @foreach($pattern['grid'] as $row)
            <div class="row">
                @foreach($row as $dot)
                <div class="dot" 
                     data-x="{{ $dot['x'] }}" 
                     data-y="{{ $dot['y'] }}">
                </div>
                @endforeach
            </div>
            @endforeach
        </div>

        <div id="resultInfo" class="result-info">
            <p>Требуемая длина пароля: <span id="requiredLength"></span></p>
            <p id="patternResult" class="pattern-result"></p>
        </div>

        <div id="solutionContainer" class="solution-container">
            <button id="showSolutionBtn" class="show-solution-btn">Показать решение</button>
            <div id="solutionPattern" class="pattern-container solution-pattern hidden">
                <!-- Сетка для решения будет создана динамически -->
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('patternContainer');
            if (!container) return; // Выходим, если контейнер не найден
            
            const allowReconnect = container.dataset.allowReconnect === 'true';
            const allowDiagonal = container.dataset.allowDiagonal === 'true';
            const dots = container.getElementsByClassName('dot');
            let selectedDots = [];
            let lines = [];
            let isDrawing = false;
            let currentDot = null;
            let patternCoordinates = [];
            let targetPattern = null;
            let startDot = null;
            let endDot = null;

            const resultInfo = document.getElementById('resultInfo');
            const requiredLength = document.getElementById('requiredLength');
            const patternResult = document.getElementById('patternResult');

            // Предотвращаем скролл при рисовании паттерна
            container.addEventListener('touchmove', function(e) {
                if (isDrawing) {
                    e.preventDefault();
                }
            }, { passive: false });

            function areNeighbors(dot1, dot2) {
                const x1 = parseInt(dot1.dataset.x);
                const y1 = parseInt(dot1.dataset.y);
                const x2 = parseInt(dot2.dataset.x);
                const y2 = parseInt(dot2.dataset.y);
                
                const dx = Math.abs(x1 - x2);
                const dy = Math.abs(y1 - y2);
                
                if (allowDiagonal) {
                    // Разрешаем диагональные соединения
                    return (dx <= 1 && dy <= 1) && !(dx === 0 && dy === 0);
                } else {
                    // Только горизонтальные и вертикальные соединения
                    return ((dx === 1 && dy === 0) || (dx === 0 && dy === 1));
                }
            }

            function drawLine(dot1, dot2) {
                const rect1 = dot1.getBoundingClientRect();
                const rect2 = dot2.getBoundingClientRect();
                const containerRect = container.getBoundingClientRect();

                const x1 = rect1.left + rect1.width/2 - containerRect.left;
                const y1 = rect1.top + rect1.height/2 - containerRect.top;
                const x2 = rect2.left + rect2.width/2 - containerRect.left;
                const y2 = rect2.top + rect2.height/2 - containerRect.top;

                const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
                const angle = Math.atan2(y2 - y1, x2 - x1);

                const line = document.createElement('div');
                line.className = 'line';
                line.style.width = `${length}px`;
                line.style.left = `${x1}px`;
                line.style.top = `${y1}px`;
                line.style.transform = `rotate(${angle}rad)`;

                container.appendChild(line);
                lines.push(line);
            }

            function updatePatternCoordinates() {
                patternCoordinates = selectedDots.map(dot => ({
                    x: parseInt(dot.dataset.x),
                    y: parseInt(dot.dataset.y)
                }));
                console.log('Текущий паттерн:', patternCoordinates);
            }

            function getDotFromPoint(x, y) {
                const elements = document.elementsFromPoint(x, y);
                return elements.find(el => el.classList.contains('dot'));
            }

            function handleStart(e) {
                const touch = e.type === 'touchstart' ? e.touches[0] : e;
                const targetDot = getDotFromPoint(touch.clientX, touch.clientY);
                
                if (!targetDot) return;

                // Проверяем, начинает ли пользователь с начальной точки
                if (selectedDots.length === 0 && targetDot !== startDot) {
                    return; // Запрещаем начинать не с начальной точки
                }

                if (selectedDots.length > 0 && targetDot === selectedDots[selectedDots.length - 1]) {
                    isDrawing = true;
                    currentDot = targetDot;
                    return;
                }

                // Очищаем предыдущий паттерн
                selectedDots.forEach(dot => {
                    if (dot !== startDot && dot !== endDot) {
                        dot.style.backgroundColor = '#cbd5e0';
                    }
                    dot.classList.remove('selected');
                });
                lines.forEach(line => line.remove());
                lines = [];
                selectedDots = [];
                patternCoordinates = [];

                isDrawing = true;
                selectedDots = [targetDot];
                currentDot = targetDot;
                if (targetDot !== startDot && targetDot !== endDot) {
                    targetDot.style.backgroundColor = '#667eea';
                }
                targetDot.classList.add('selected');

                updatePatternCoordinates();
            }

            function handleMove(e) {
                if (!isDrawing) return;

                const touch = e.type === 'touchmove' ? e.touches[0] : e;
                const targetDot = getDotFromPoint(touch.clientX, touch.clientY);

                if (!targetDot) return;
                if (currentDot !== selectedDots[selectedDots.length - 1]) return;
                if (!areNeighbors(currentDot, targetDot)) return;
                if (!allowReconnect && selectedDots.includes(targetDot)) return;
                if (allowReconnect && targetDot === selectedDots[selectedDots.length - 2]) return;
                if (targetDot === currentDot) return;

                selectedDots.push(targetDot);
                currentDot = targetDot;
                targetDot.classList.add('selected');
                if (targetDot !== startDot && targetDot !== endDot) {
                    targetDot.style.backgroundColor = '#667eea';
                }
                drawLine(selectedDots[selectedDots.length - 2], targetDot);

                updatePatternCoordinates();
            }

            function handleEnd() {
                isDrawing = false;
                localStorage.setItem('savedPattern', JSON.stringify(patternCoordinates));
                
                if (targetPattern) {
                    const isCorrect = checkPattern();
                    updateResultDisplay(isCorrect);
                }
            }

            // Добавляем обработчики для мыши
            Array.from(dots).forEach(dot => {
                dot.addEventListener('mousedown', handleStart);
                dot.addEventListener('mouseenter', handleMove);
            });
            document.addEventListener('mouseup', handleEnd);

            // Добавляем обработчики для сенсорных событий
            container.addEventListener('touchstart', handleStart);
            container.addEventListener('touchmove', handleMove);
            container.addEventListener('touchend', handleEnd);

            const clearButton = document.createElement('button');
            clearButton.textContent = 'Очистить';
            clearButton.id = 'clearButton';
            container.parentNode.insertBefore(clearButton, container.nextSibling);

            clearButton.addEventListener('click', function() {
                selectedDots.forEach(dot => {
                    if (dot !== startDot && dot !== endDot) {
                        dot.style.backgroundColor = '#cbd5e0';
                    }
                    dot.classList.remove('selected');
                });
                selectedDots = [];
                lines.forEach(line => line.remove());
                lines = [];
                currentDot = null;
                patternCoordinates = [];
                localStorage.removeItem('savedPattern');
                
                Array.from(dots).forEach(dot => {
                    if (dot !== startDot && dot !== endDot) {
                        dot.style.backgroundColor = '#cbd5e0';
                    }
                    dot.classList.remove('selected');
                });
                
                console.log('Паттерн очищен:', patternCoordinates);
                updateResultDisplay(null);

                if (solutionPattern) {
                    solutionPattern.classList.remove('visible');
                    setTimeout(() => {
                        solutionPattern.classList.add('hidden');
                    }, 300);
                    if (showSolutionBtn) {
                        showSolutionBtn.textContent = 'Показать решение';
                    }
                }
            });

            // Функция для генерации случайного паттерна
            function generateTargetPattern(gridSize, length) {
                let attempts = 0;
                const maxAttempts = 100;
                
                while (attempts < maxAttempts) {
                    let pattern = [];
                    let currentDot = {
                        x: Math.floor(Math.random() * gridSize),
                        y: Math.floor(Math.random() * gridSize)
                    };
                    pattern.push({...currentDot});

                    // Генерируем путь нужной длины
                    while (pattern.length < length) {
                        let possibleMoves = [];
                        
                        // Проверяем все возможные соседние точки
                        for (let dx = -1; dx <= 1; dx++) {
                            for (let dy = -1; dy <= 1; dy++) {
                                if (dx === 0 && dy === 0) continue;
                                if (!allowDiagonal && Math.abs(dx) + Math.abs(dy) > 1) continue;
                                
                                let newX = currentDot.x + dx;
                                let newY = currentDot.y + dy;
                                
                                if (newX >= 0 && newX < gridSize && 
                                    newY >= 0 && newY < gridSize && 
                                    !pattern.some(dot => dot.x === newX && dot.y === newY)) {
                                    possibleMoves.push({x: newX, y: newY});
                                }
                            }
                        }
                        
                        if (possibleMoves.length === 0) break;
                        
                        // Выбираем случайное следующее движение
                        let nextMove = possibleMoves[Math.floor(Math.random() * possibleMoves.length)];
                        pattern.push(nextMove);
                        currentDot = nextMove;
                    }
                    
                    if (pattern.length === length) {
                        targetPattern = pattern;
                        console.log('Загаданный паттерн:', targetPattern);
                        return targetPattern;
                    }
                    
                    attempts++;
                }
                
                return null;
            }

            // Функция для проверки совпадения паттернов
            function checkPattern() {
                if (!targetPattern || patternCoordinates.length !== targetPattern.length) return false;
                
                return patternCoordinates.every((dot, index) => 
                    dot.x === targetPattern[index].x && dot.y === targetPattern[index].y
                );
            }

            // Обновленная функция отображения начальной и конечной точек
            function showStartEndPoints() {
                if (!targetPattern || targetPattern.length < 2) return;
                
                startDot = Array.from(dots).find(dot => 
                    parseInt(dot.dataset.x) === targetPattern[0].x && 
                    parseInt(dot.dataset.y) === targetPattern[0].y
                );
                
                endDot = Array.from(dots).find(dot => 
                    parseInt(dot.dataset.x) === targetPattern[targetPattern.length - 1].x && 
                    parseInt(dot.dataset.y) === targetPattern[targetPattern.length - 1].y
                );
                
                if (startDot) {
                    startDot.style.backgroundColor = '#ff4444';
                    addDotLabel(startDot, 'Н');
                }
                if (endDot) {
                    endDot.style.backgroundColor = '#ff4444';
                    addDotLabel(endDot, 'К');
                }
            }

            function updateResultDisplay(isCorrect = null) {
                if (!targetPattern) return;
                
                requiredLength.textContent = targetPattern.length;
                
                if (isCorrect === null) {
                    patternResult.textContent = 'Нарисуйте паттерн';
                    patternResult.className = 'pattern-result';
                } else if (isCorrect) {
                    patternResult.textContent = 'Верно! Паттерн совпадает!';
                    patternResult.className = 'pattern-result correct';
                } else {
                    patternResult.textContent = 'Неверно. Попробуйте еще раз.';
                    patternResult.className = 'pattern-result incorrect';
                }
            }

            // Инициализация при загрузке
            const gridSizeInput = document.getElementById('gridSize');
            const patternLengthInput = document.getElementById('patternLength');
            
            if (container && gridSizeInput && patternLengthInput) {
                const gridSize = Math.sqrt(dots.length);
                const patternLength = parseInt(container.dataset.patternLength) || 3;
                
                if (patternLength < 3) {
                    console.error('Минимальная длина пароля должна быть 3 точки!');
                    return;
                }
                
                if (!targetPattern) {
                    targetPattern = generateTargetPattern(gridSize, patternLength);
                    console.log('Загаданный паттерн:', targetPattern);
                }
                
                showStartEndPoints();
                updateResultDisplay(null);
            }

            // Обработчик формы
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const gridSize = parseInt(gridSizeInput.value);
                    const patternLength = parseInt(patternLengthInput.value);
                    
                    if (gridSize < 3) {
                        alert('Минимальный размер сетки должен быть 3x3!');
                        return;
                    }
                    
                    if (patternLength < 3) {
                        alert('Минимальная длина пароля должна быть 3 точки!');
                        return;
                    }
                    
                    if (patternLength > gridSize * gridSize) {
                        alert('Длина пароля не может быть больше количества точек!');
                        return;
                    }
                    
                    const newPattern = generateTargetPattern(gridSize, patternLength);
                    
                    if (newPattern) {
                        targetPattern = newPattern;
                        console.log('Загаданный паттерн:', targetPattern);
                        this.submit();
                    } else {
                        alert('Не удалось сгенерировать паттерн. Попробуйте другие параметры.');
                    }
                });
            }

            // Добавляем функционал для отображения решения
            const showSolutionBtn = document.getElementById('showSolutionBtn');
            const solutionPattern = document.getElementById('solutionPattern');

            function createSolutionGrid(gridSize) {
                solutionPattern.innerHTML = '';
                
                for (let y = 0; y < gridSize; y++) {
                    const row = document.createElement('div');
                    row.className = 'row';
                    
                    for (let x = 0; x < gridSize; x++) {
                        const dot = document.createElement('div');
                        dot.className = 'dot';
                        dot.dataset.x = x;
                        dot.dataset.y = y;
                        row.appendChild(dot);
                    }
                    
                    solutionPattern.appendChild(row);
                }
            }

            function addDotLabel(dot, label) {
                // Удаляем существующую метку, если она есть
                const existingLabel = dot.querySelector('.dot-label');
                if (existingLabel) {
                    existingLabel.remove();
                }
                
                const labelElement = document.createElement('span');
                labelElement.className = 'dot-label';
                labelElement.textContent = label;
                dot.appendChild(labelElement);
            }

            function drawSolutionPattern(pattern) {
                if (!pattern || !solutionPattern) return;

                const dots = solutionPattern.getElementsByClassName('dot');
                
                // Удаляем старые линии
                const oldLines = solutionPattern.getElementsByClassName('line');
                Array.from(oldLines).forEach(line => line.remove());

                // Очищаем точки и метки
                Array.from(dots).forEach(dot => {
                    dot.style.backgroundColor = '#cbd5e0';
                    dot.classList.remove('selected');
                    const label = dot.querySelector('.dot-label');
                    if (label) label.remove();
                });
                
                // Отмечаем точки и рисуем линии
                pattern.forEach((point, index) => {
                    const dot = Array.from(dots).find(d => 
                        parseInt(d.dataset.x) === point.x && 
                        parseInt(d.dataset.y) === point.y
                    );
                    
                    if (dot) {
                        dot.classList.add('selected');
                        if (index === 0) {
                            dot.style.backgroundColor = '#ff4444';
                            addDotLabel(dot, 'Н');
                        } else if (index === pattern.length - 1) {
                            dot.style.backgroundColor = '#ff4444';
                            addDotLabel(dot, 'К');
                        } else {
                            dot.style.backgroundColor = '#667eea';
                        }
                        
                        // Рисуем линию к предыдущей точке
                        if (index > 0) {
                            const prevPoint = pattern[index - 1];
                            const prevDot = Array.from(dots).find(d => 
                                parseInt(d.dataset.x) === prevPoint.x && 
                                parseInt(d.dataset.y) === prevPoint.y
                            );
                            
                            if (prevDot) {
                                const rect1 = prevDot.getBoundingClientRect();
                                const rect2 = dot.getBoundingClientRect();
                                const containerRect = solutionPattern.getBoundingClientRect();

                                const x1 = rect1.left + rect1.width/2 - containerRect.left;
                                const y1 = rect1.top + rect1.height/2 - containerRect.top;
                                const x2 = rect2.left + rect2.width/2 - containerRect.left;
                                const y2 = rect2.top + rect2.height/2 - containerRect.top;

                                const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
                                const angle = Math.atan2(y2 - y1, x2 - x1);

                                const line = document.createElement('div');
                                line.className = 'line';
                                line.style.width = `${length}px`;
                                line.style.left = `${x1}px`;
                                line.style.top = `${y1}px`;
                                line.style.transform = `rotate(${angle}rad)`;

                                solutionPattern.appendChild(line);
                            }
                        }
                    }
                });
            }

            if (showSolutionBtn && solutionPattern) {
                const gridSize = Math.sqrt(dots.length);
                createSolutionGrid(gridSize);

                showSolutionBtn.addEventListener('click', function() {
                    if (solutionPattern.classList.contains('hidden')) {
                        solutionPattern.classList.remove('hidden');
                        solutionPattern.classList.add('visible');
                        drawSolutionPattern(targetPattern);
                        showSolutionBtn.textContent = 'Скрыть решение';
                    } else {
                        const oldLines = solutionPattern.getElementsByClassName('line');
                        Array.from(oldLines).forEach(line => line.remove());
                        solutionPattern.classList.remove('visible');
                        solutionPattern.classList.add('hidden');
                        showSolutionBtn.textContent = 'Показать решение';
                    }
                });
            }
        });
    </script>
</body>
</html> 