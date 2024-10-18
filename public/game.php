<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brian Clincy Tic-Tac-Toe Game :: PHP STYLE</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <form action="index.php" method="POST" id="board">
        <h1>Brian Tic-Tac-Toe Game</h1>
        <div id="splash-message"></div>
        <div class="grid-container">
            <div class="score">
                <input type="text" id="name" placeholder="Name" />
                <div>Wins: <?php echo $wins ?? ''; ?></div>
            </div>
            <div class="board">
                <?php for ($i = 0; $i < 9; $i++): ?>
                    <button type="submit" id="box<?php echo $i; ?>" class="cell" name="<?php echo $i; ?>"
                        value="<?php echo $i; ?>">
                    </button>
                <?php endfor; ?>
            </div>
        </div>
    </form>
    <script>
        const buttons = document.querySelectorAll('button');
        const btns = document.querySelectorAll('.cell');

        async function boardState() {
            const fields = {};
            buttons.forEach(button => {
                fields[button.name] = button.value;
            });

            return fields;
        }
        async function sendData(data) {
            const formData = new FormData();
            const fields = await boardState();
            formData.append("name", document.querySelector('#name').value);
            formData.append("cells", JSON.stringify(fields));

            try {
                const response = await fetch("/api", {
                    method: "POST",
                    // Set the FormData instance as the request body
                    body: formData,
                });
                let api = await response.json();
                if (api.winner !== undefined && api.winner !== null) {
                    showValidationMessage('success', 'Winner is ' + api.winner);
                }
                if (api.tie !== undefined && api.tie === true) {
                    showValidationMessage('success', 'It\'s a Tie');
                }
                if (api.compTurn !== undefined) {
                    updateBoard(api);
                }
                console.log(api);
            } catch (e) {
                console.error(e);
            }
        }
        function updateBoard(api) {
            let play = document.getElementById('box' + api.compTurn);
            play.innerText = 'O';
            play.value = 'O';
            play.style.backgroundColor = 'wheat';
        }

        const send = document.querySelector("#board");

        send.addEventListener("submit", (e) => {
            e.preventDefault();
            if (e.submitter.innerText.length === 0) {
                e.submitter.innerText = 'X';
                e.submitter.value = 'X';
                e.submitter.style.backgroundColor = 'cadetblue';
                sendData()
            }
            else {
                showValidationMessage('error', 'Invalid Move');
            }

        });
        function showValidationMessage(type, message) {
            const splashElement = document.getElementById('splash-message');
            splashElement.textContent = message;
            splashElement.className = type;
            splashElement.style.opacity = '1';

            setTimeout(() => {
                splashElement.style.opacity = '0';
            }, 3000);
        }
    </script>
</body>

</html>