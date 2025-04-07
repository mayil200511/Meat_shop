<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculator</title>
    <style>
        body {
            background-color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
           margin: 0;
        }
        .calculator {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .display {
            width: 100%;
            height: 50px;
            border: none;
            background-color: #f0f0f0;
            text-align: right;
            padding: 10px;
            font-size: 24px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .button {
            padding: 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            background-color: #e0e0e0;
            cursor: pointer;
        }
        .button.operator {
            background-color: #ff9500;
            color: #fff;
        }
    </style>
  
</head>
<body>
    <div class="calculator">
        <input type="text" class="display" disabled>
        <div class="buttons">
            <button class="button">⏱️</button>
            <button class="button">7</button>
            <button class="button">8</button>
            <button class="button">9</button>
            <button class="button operator">/</button>
            <button class="button">4</button>
            <button class="button">5</button>
            <button class="button">6</button>
            <button class="button operator">*</button>
            <button class="button">1</button>
            <button class="button">2</button>
            <button class="button">3</button>
            <button class="button operator">-</button>
            <button class="button">0</button>
            <button class="button">00</button>
            <button class="button">.</button>
            <button class="button operator">+</button>
            <button class="button operator">%</button>
            <button class="button">C</button>
            <button class="button operator">=</button>
           
        </div>
    </div>
    <div
         style="position: fixed; bottom: 10px; right: 10px;">
            <button onclick="window.location.href='tools.php'" style="border-radius: 50%; transition: transform 0.3s ease;">
                <img src="image/exit_icon.png" alt="Exit" style="width: 50px; height: 50px;">
            </button>
        </div>
</body>
    <script>
        const display = document.querySelector('.display');
        const buttons = document.querySelectorAll('.button');
        let currentInput = '';
        let operator = '';
        let previousInput = '';
        let operationHistory = '';
        let history = [];

        buttons.forEach(button => {
            button.addEventListener('click', () => {
                const value = button.textContent;

                if (value === 'C') {
                    currentInput = '';
                    operator = '';
                    previousInput = '';
                    operationHistory = '';
                    display.value = '';
                } else if (value === '=') {
                    if (operator && previousInput !== '') {
                        try {
                            if (operator === '√') {
                                currentInput = Math.sqrt(parseFloat(previousInput));
                            } else if (operator === '%') {
                                currentInput = parseFloat(previousInput) % parseFloat(currentInput);
                            } else {
                                currentInput = new Function('return ' + previousInput + operator + currentInput)();
                            }
                            display.value = currentInput;
                            history.push(`${operationHistory} ${currentInput}`);
                        } catch (error) {
                            display.value = 'Error';
                        }
                        operator = '';
                        previousInput = '';
                        operationHistory = '';
                        currentInput = ''; // Clear the input after showing the output
                    }
                } else if (['+', '-', '*', '/', '√', '%'].includes(value)) {
                    if (currentInput !== '') {
                        operator = value;
                        previousInput = currentInput;
                        currentInput = '';
                        operationHistory += ` ${previousInput} ${operator}`;
                        display.value = operationHistory;
                    }
                } else if (value === '⏱️') {
                    alert('Last 5 operations:\n' + history.slice(-5).join('\n'));
                } else {
                    currentInput += value;
                    display.value = `${operationHistory} ${currentInput}`;
                }
            });
        });
    </script>
</html>