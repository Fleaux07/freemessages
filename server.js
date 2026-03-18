const express = require('express');
const http = require('http');
const { Server } = require('socket.io');

const app = express();
const server = http.createServer(app);


const io = new Server(server, {
    cors: { origin: "*" } 
});

io.on('connection', (socket) => {
    console.log('Un utilisateur est connecté ! ID:', socket.id);

    socket.on('rejoindre_canal', (cle) => {
        socket.join(cle);
        console.log(`L'utilisateur ${socket.id} a rejoint le canal : ${cle}`);
        
        socket.to(cle).emit('nouveau_message', '💬 Un nouvel utilisateur a rejoint le canal !');
    });

    
    socket.on('envoyer_message', (data) => {
        io.to(data.cle).emit('nouveau_message', data.texte);
    });

    socket.on('disconnect', () => {
        console.log('Un utilisateur s\'est déconnecté');
    });
});

server.listen(3000, () => {
    console.log('Moteur de chat activé sur le port 3000 !');
});