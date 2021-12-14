require('dotenv').config()

const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
    allowEIO3: true,
    cors: {
        origin: process.env.ORIGIN,
        methods: ["GET", "POST"],
        credentials: true
    }
})
httpServer.listen(process.env.PORT || 8081, function () {
    console.log(`listening on *:${process.env.PORT}`)
})

io.on('connection', ws => {
    console.log(`client ${ws.id} has connected`)

    ws.on('newCategory', function (category) {
        ws.to(ws.userid).emit('newCategory', category)
    })
    ws.on('updateCategory', function (category) {
        console.log("updating category " + JSON.stringify(category))
        ws.to(ws.userid).emit('updateCategory', category)
    })
    ws.on('deleteCategory', function (category) {
        ws.to(ws.userid).emit('deleteCategory', category)
    })

    ws.on('newCreditTransaction', transaction => {
        console.log(transaction)
        ws.to(transaction.payment_reference.toString()).emit('newCreditTransaction',transaction)
    })

    ws.on('blockVcard', vcard => {
        console.log(`blocking vcard ${vcard.phone_number}`)

        ws.to(vcard.phone_number.toString()).emit('blockVcard')
    })

    ws.on('logged_in', user => {
        console.log({id: user.id})
        ws.join(user.id)
        ws.userid = user.id

        console.log(`user ${user.id} has logged in`)

        switch(user.user_type){
            case 'A':
                ws.join('administrator')
                break;
            case 'V':
                ws.join('vcard')
            default:
                break;
        }
    })

    ws.on('logged_out',user => {
        ws.leave(user.id)
        delete ws.userid

        console.log(`user ${user.id} has logged out`)

        switch(user.user_type){
        case 'A':
            ws.leave('administrator')
            break;
        case 'V':
            ws.leave('vcard')
        default:
            break;
        }
    })



})
