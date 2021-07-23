<html>
    <head>
        <style>
            input, button { padding: 10px; }
        </style>
    </head>
    <body>
        <input type="text" id="message" />
        <button onclick="transmitMessage()">Send</button>
        <button onclick="transmitMessage2()">Send</button>
        <script>
            // Create a new WebSocket.
            var socket  = new WebSocket('ws://localhost:5555');

            // Define the 
            var message = document.getElementById('message');

            function transmitMessage() {
                socket.send( message.value );
            }
            var obj = {
                name: "ahmed",
                age: 23
            }
            function transmitMessage2() {
                socket.send(JSON.stringify(obj));
            }

            socket.onmessage = function(e) {
                alert( e.data );
            }
        </script>
    </body>
</html>