// scripts.js

// اختيار عناصر DOM
const chatContainer = document.querySelector('.chat-container');
const chatInput = document.querySelector('.chat-input input[type="text"]');
const sendButton = document.querySelector('.chat-input .btn-send');

// إضافة دالة لإرسال الرسالة
function sendMessage() {
    const messageText = chatInput.value.trim();

    if (messageText !== '') {
        // إنشاء عنصر لعرض الرسالة
        const messageElement = document.createElement('div');
        messageElement.classList.add('message');
        messageElement.textContent = messageText;

        // إضافة الرسالة إلى عنصر الدردشة
        chatContainer.appendChild(messageElement);

        // مسح محتوى حقل الإدخال بعد إرسال الرسالة
        chatInput.value = '';
    }
}

// إضافة استماع لحدث النقر على زر الإرسال
sendButton.addEventListener('click', sendMessage);

// إضافة استماع لحدث الضغط على مفتاح Enter
chatInput.addEventListener('keypress', function (event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
});
