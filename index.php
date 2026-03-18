<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma Messagerie Secrète</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        #zone-chat { display: none; margin-top: 20px; border-top: 2px solid #ccc; padding-top: 20px;}
        #messages { height: 200px; border: 1px solid #999; overflow-y: scroll; padding: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Messagerie Secrète</h1>

    <div id="zone-connexion">
        <input type="text" id="input-cle" placeholder="Clé secrète (ex: toto123)">
        <button onclick="rejoindre()">Rejoindre le canal</button>
    </div>

    <div id="zone-chat">
        <h2>Canal : <span id="nom-canal"></span></h2>
        <div id="messages"></div>
        <input type="text" id="input-message" placeholder="Écris ton message...">
        <button onclick="envoyer()">Envoyer</button>
    </div>

    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <script>
        const socket = io('http://localhost:3000');
        let cleActuelle = ''; // On mémorise la clé du canal

        // 1. Fonction pour rejoindre un canal
        function rejoindre() {
            const cle = document.getElementById('input-cle').value;
            if (cle.trim() !== '') {
                cleActuelle = cle;
                // On prévient le serveur qu'on veut rejoindre cette clé
                socket.emit('rejoindre_canal', cleActuelle);
                
                // On cache la connexion et on affiche le chat
                document.getElementById('zone-connexion').style.display = 'none';
                document.getElementById('zone-chat').style.display = 'block';
                document.getElementById('nom-canal').innerText = cleActuelle;
            }
        }

        // 2. Fonction pour envoyer un message
        function envoyer() {
            const texte = document.getElementById('input-message').value;
            if (texte.trim() !== '') {
                // On envoie le message ET la clé au serveur
                socket.emit('envoyer_message', { cle: cleActuelle, texte: texte });
                document.getElementById('input-message').value = ''; // On vide le champ
            }
        }

        // 3. Quand on reçoit un message du serveur
        socket.on('nouveau_message', (messageRecu) => {
            const boiteMessages = document.getElementById('messages');
            boiteMessages.innerHTML += `<p>${messageRecu}</p>`;
            // On scroll tout en bas automatiquement
            boiteMessages.scrollTop = boiteMessages.scrollHeight;
        });
    </script>
</body>
</html>