const io = require('socket.io')(4000, {cors:'*'})

io.on("connection", (socket)=>{
    socket.on('join', (username, userroom, userphoto)=>{
        socket.name = username
        socket.photo = userphoto
        socket.room = userroom

        socket.join(userroom)
        socket.to(userroom).emit('receive', `${socket.name} joined`, socket.id, socket.photo)
    })

    socket.on('send', (message)=>{
        socket.to(socket.room).emit('receive', message, socket.id, socket.photo)
    })

    socket.on('disconnect', () => {
        socket.to(socket.room).emit('receive', `${socket.name} disconnected`, socket.id, socket.photo)
    });
})


////////////////////////////////////////////////// dialogflowClient.js ////////////////////////////////////

// const dialogflow = require('@google-cloud/dialogflow');
// const app = require('express')();
// const cors = require('cors');
// const path = require('path');

// const sessionClient = new dialogflow.SessionsClient({
//   keyFilename: path.join(__dirname, 'credentials.json')
// });

// async function detectIntent(sessionId, text) {
//   const sessionPath = sessionClient.projectAgentSessionPath('projectid-4444', sessionId);
//   const request = {
//     session: sessionPath,
//     queryInput: {
//       text: {
//         text: text,
//         languageCode: 'en',
//       },
//     },
//   };

//   const [response] = await sessionClient.detectIntent(request);
//   const result = response.queryResult;
//   return result.fulfillmentText;
// }

// app.use(cors());
// app.get('/', async(req, res)=>{
//   try {
//     const botResponse = await detectIntent('sessionId', req.query.message);
//     res.json({answer:botResponse});
//   } catch (error) {console.log('errror : ' + error); res.send('errror : ' + error);}
// });

// app.listen(5000, ()=>{console.log('listening on : 5000')});