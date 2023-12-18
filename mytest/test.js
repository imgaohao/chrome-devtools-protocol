// 创建一个 WebSocket 连接
const socket = new WebSocket('ws://127.0.0.1:9222/devtools/page/61545B184DE9F73B3DA617F09F6688BB');
var requestId = "";

// 监听连接事件
socket.addEventListener('open', (event) => {
    console.log('Connected to Chrome DevTools Protocol');
});

// 监听消息事件
socket.addEventListener('message', (event) => {
    const message = JSON.parse(event.data);

    if (message.method == "Network.requestWillBeSent") {
        console.log('requestWillBeSent:', message.params.request.url);
        if (requestId == "" && message.params.request.url.indexOf("aweme/v1/web/aweme/detail") != -1) {
            requestId = message.requestId;
            console.log('requestId:', requestId);
        }
    }
});

// 监听错误事件
socket.addEventListener('error', (event) => {
    console.error('WebSocket error:', event);
});

// 监听关闭事件
socket.addEventListener('close', (event) => {
    console.log('Connection to Chrome DevTools Protocol closed');
});



socket.send(JSON.stringify({ id: 1, method: 'Network.enable', params: {} }));

socket.close();
