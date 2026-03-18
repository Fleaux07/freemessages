<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Messagerie Secrète</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>💬 Messagerie</h1>
            <p id="info-canal" style="display: none;">Canal : <span id="nom-canal"></span></p>
        </div>

        <div id="zone-connexion">
            <h2>Rejoindre un salon</h2>
            <input type="text" id="input-cle" placeholder="Clé secrète (ex: toto123)">
            <button onclick="rejoindre()">Rejoindre</button>
        </div>

        <div id="zone-chat">
            <div id="messages">
                </div>
            
            <div class="input-zone">
                <input type="text" id="input-message" placeholder="Écris ton message...">
                <button onclick="envoyer()">Envoyer</button>
            </div>
        </div>
    </div>

    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script>
        const socket = io('http://localhost:3000');
        const champMessage = document.getElementById('input-message');
        const champCode = document.getElementById('input-cle');
        let cleActuelle = '';


        champMessage.addEventListener('keydown', function (event) {
            if (event.key == 'Enter'){
                envoyer();
            }
        });


        function rejoindre() {
            const cle = document.getElementById('input-cle').value;
            if (cle.trim() !== '') {
                cleActuelle = cle;
                socket.emit('rejoindre_canal', cleActuelle);
                
                document.getElementById('zone-connexion').style.display = 'none';
                document.getElementById('zone-chat').style.display = 'flex'; 
                document.getElementById('info-canal').style.display = 'block';
                document.getElementById('nom-canal').innerText = cleActuelle;
            }
        }

        function envoyer() {
            const texte = document.getElementById('input-message').value;
            if (texte.trim() !== '') {
                socket.emit('envoyer_message', { cle: cleActuelle, texte: texte });
                document.getElementById('input-message').value = ''; 
            }
        }

        socket.on('nouveau_message', (messageRecu) => {
            const boiteMessages = document.getElementById('messages');
            boiteMessages.innerHTML += `<div class="message-bulle">${messageRecu}</div>`;
            boiteMessages.scrollTop = boiteMessages.scrollHeight;
        });
    </script>
</body>
</html>