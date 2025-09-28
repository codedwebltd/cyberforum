@extends('inc.home.app')
@section('title', 'Chat with ' . $chatUser->name . ' - ' . config('app.name'))
@section('content')

<main class="p-3 sm:p-4 lg:p-6">
    <div class="mx-auto max-w-4xl">
        <!-- Breadcrumb -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg mb-6">
            <div class="p-4 sm:p-6">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 text-sm overflow-x-auto whitespace-nowrap">
                        <a href="/home" class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                            <i data-lucide="home" class="w-4 h-4"></i>
                            Home
                        </a>
                        
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        
                        <a href="{{ route('members.index') }}" class="flex items-center gap-2 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200 font-medium">
                            <i data-lucide="users" class="w-4 h-4"></i>
                            Members
                        </a>
                        
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
                        
                        <div class="flex items-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg shadow-sm">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                            <span class="font-medium">Chat</span>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
            <!-- Chat Header -->
            <div class="border-b border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                                @if($chatUser->avatar_url)
                                <img src="{{ $chatUser->avatar_url }}" alt="{{ $chatUser->name }}" class="w-full h-full object-cover">
                                @else
                                <span class="text-lg font-bold text-white">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $chatUser->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                @if($chatUser->username)
                                {{ '@' . $chatUser->username }} • 
                                @endif
                                Online now
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i data-lucide="video" class="w-5 h-5"></i>
                        </button>
                        <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i data-lucide="more-vertical" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Messages Area -->
            <div class="h-96 sm:h-[500px] overflow-y-auto p-4 sm:p-6 space-y-4" id="messages-container">
                <!-- Sample Messages -->
                <div class="flex justify-start">
                    <div class="flex items-start space-x-3 max-w-xs sm:max-w-md">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center flex-shrink-0">
                            @if($chatUser->avatar_url)
                            <img src="{{ $chatUser->avatar_url }}" alt="{{ $chatUser->name }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-sm font-bold text-white">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-lg px-4 py-3">
                            <p class="text-gray-900 dark:text-white text-sm">Hey! How are you doing today?</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">2:30 PM</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <div class="flex items-start space-x-3 max-w-xs sm:max-w-md">
                        <div class="bg-blue-600 rounded-2xl rounded-tr-lg px-4 py-3">
                            <p class="text-white text-sm">I'm doing great! Thanks for asking. How about you?</p>
                            <span class="text-xs text-blue-200 mt-1 block">2:32 PM</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-start">
                    <div class="flex items-start space-x-3 max-w-xs sm:max-w-md">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center flex-shrink-0">
                            @if($chatUser->avatar_url)
                            <img src="{{ $chatUser->avatar_url }}" alt="{{ $chatUser->name }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-sm font-bold text-white">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-lg px-4 py-3">
                            <p class="text-gray-900 dark:text-white text-sm">I'm doing well too! Just working on some exciting projects. Would love to hear about what you're up to.</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">2:35 PM</span>
                        </div>
                    </div>
                </div>
                
                <!-- Typing Indicator -->
                <div class="flex justify-start" id="typing-indicator" style="display: none;">
                    <div class="flex items-start space-x-3 max-w-xs sm:max-w-md">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center flex-shrink-0">
                            @if($chatUser->avatar_url)
                            <img src="{{ $chatUser->avatar_url }}" alt="{{ $chatUser->name }}" class="w-full h-full object-cover">
                            @else
                            <span class="text-sm font-bold text-white">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-lg px-4 py-3">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Message Input -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-end space-x-3">
                    <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i data-lucide="paperclip" class="w-5 h-5"></i>
                    </button>
                    
                    <div class="flex-1 relative">
                        <textarea
                            id="message-input"
                            rows="1"
                            placeholder="Type your message..."
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                            style="max-height: 120px;"
                        ></textarea>
                    </div>
                    
                    <button id="send-button" class="p-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="flex items-center justify-between mt-3 text-xs text-gray-500 dark:text-gray-400">
                    <span>Press Enter to send, Shift+Enter for new line</span>
                    <span id="char-count">0/2000</span>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const charCount = document.getElementById('char-count');
    const messagesContainer = document.getElementById('messages-container');
    
    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        
        // Update character count
        const count = this.value.length;
        charCount.textContent = `${count}/2000`;
        
        // Enable/disable send button
        sendButton.disabled = count === 0 || count > 2000;
    });
    
    // Send message on Enter (but not Shift+Enter)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Send button click
    sendButton.addEventListener('click', sendMessage);
    
    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message || message.length > 2000) return;
        
        // Add message to chat
        const messageHtml = `
            <div class="flex justify-end">
                <div class="flex items-start space-x-3 max-w-xs sm:max-w-md">
                    <div class="bg-blue-600 rounded-2xl rounded-tr-lg px-4 py-3">
                        <p class="text-white text-sm">${message}</p>
                        <span class="text-xs text-blue-200 mt-1 block">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    </div>
                </div>
            </div>
        `;
        
        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Clear input
        messageInput.value = '';
        messageInput.style.height = 'auto';
        charCount.textContent = '0/2000';
        sendButton.disabled = true;
        
        // Simulate typing indicator
        setTimeout(() => {
            document.getElementById('typing-indicator').style.display = 'flex';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 1000);
        
        // Simulate response
        setTimeout(() => {
            document.getElementById('typing-indicator').style.display = 'none';
            
            const responses = [
                "That's really interesting!",
                "I completely agree with you.",
                "Tell me more about that.",
                "That sounds great!",
                "I hadn't thought of it that way."
            ];
            
            const response = responses[Math.floor(Math.random() * responses.length)];
            const responseHtml = `
                <div class="flex justify-start">
                    <div class="flex items-start space-x-3 max-w-xs sm:max-w-md">
                        <div class="w-8 h-8 rounded-lg overflow-hidden bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center flex-shrink-0">
                            ${document.querySelector('#messages-container .w-8 img') ? document.querySelector('#messages-container .w-8 img').outerHTML : '<span class="text-sm font-bold text-white">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</span>'}
                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-lg px-4 py-3">
                            <p class="text-gray-900 dark:text-white text-sm">${response}</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                        </div>
                    </div>
                </div>
            `;
            
            messagesContainer.insertAdjacentHTML('beforeend', responseHtml);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 3000);
    }
    
    // Auto-scroll to bottom on load
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
});
</script>

@endsection