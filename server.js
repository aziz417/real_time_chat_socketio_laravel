import express from 'express';
import { createServer } from 'node:http';
import { Server } from 'socket.io';

const app = express();
const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: "http://127.0.0.1:8000",
        methods: ["GET", "POST"],
    }
});
const users = {}; // To store User IDs and their corresponding Socket IDs

io.on('connection', (socket) => {
    console.log('A new user has connected');

    //active users
    socket.on("user_connected", function (user_id) {
        users[user_id] = socket.id;
        io.emit('activeUsers', Object.keys(users));
    });

    // Event to register a user
    socket.on('registerUser', (userId) => {
        users[userId] = socket.id; // Store the user's Socket ID
        console.log(`User registered: ${userId}`);
    });

    // Event to send a private message
    socket.on('sendPrivateMessage', (data) => {
        const { recipientId, message } = data;
        const recipientSocketId = users[recipientId]; // Get the recipient's Socket ID

        console.log('recipientSocketId', recipientSocketId);
        

        if (recipientSocketId) {
            io.to(recipientSocketId).emit('receiveMessage', message); // Send the message to the recipient
            console.log(`Message sent to user with ID ${recipientId}: ${message}`);
        } else {
            console.log('Recipient is not connected'); // Handle the case where the recipient is not connected
        }
    });

    // Event to send a private message typing
    socket.on('sendPrivateMessageTyping', (data) => {
        const { recipientId, typeMessage } = data;
        const recipientSocketId = users[recipientId]; // Get the recipient's Socket ID

        if (recipientSocketId) {
            io.to(recipientSocketId).emit('receiveMessageTyping', typeMessage); // Send the message to the recipient
        } else {
            console.log('Recipient is not connected'); // Handle the case where the recipient is not connected
        }
    });


    // Handle user disconnect
    socket.on('disconnect', () => {
        for (let userId in users) {
            if (users[userId] === socket.id) {
                delete users[userId]; // Remove the user from the list of connected users
                console.log(`User disconnected: ${userId}`);
                break;
            }
        }
    });
});

server.listen(3000, () => {
    console.log('Server is running: http://localhost:3000'); // Start the server
});
