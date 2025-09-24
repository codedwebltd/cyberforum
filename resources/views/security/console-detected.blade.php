<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { user-select: none; -webkit-user-select: none; }
        .pulse-red { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0%, 100% { background-color: rgb(239 68 68); }
            50% { background-color: rgb(220 38 38); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen text-white bg-gray-900">
    <div class="max-w-md p-8 mx-auto text-center">
        <div class="mb-8">
            <div class="flex items-center justify-center w-20 h-20 mx-auto mb-4 rounded-full pulse-red">
                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="mb-2 text-3xl font-bold text-red-400">Access Restricted</h1>
            <p class="text-gray-300">Developer tools detected</p>
        </div>

        <div class="p-6 mb-6 bg-gray-800 rounded-lg">
            <p class="mb-4 text-sm text-gray-400">
                For security reasons, access to developer tools is restricted on this platform.
            </p>
            <div class="space-y-1 text-xs text-gray-500">
                <p>Time: {{ $timestamp }}</p>
                <p>IP: {{ $ip }}</p>
                <p>This activity has been logged for security purposes.</p>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('dashboard') }}"
               class="block w-full px-4 py-3 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                Return to Dashboard
            </a>
            <a href="{{ route('home') }}"
               class="block w-full px-4 py-3 font-medium text-white transition-colors bg-gray-700 rounded-lg hover:bg-gray-600">
                Go to Homepage
            </a>
        </div>

        <p class="mt-6 text-xs text-gray-500">
            If you believe this is an error, please contact support.
        </p>
    </div>

    <script>
        // Additional security measures
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' || (e.ctrlKey && e.shiftKey && e.key === 'I')) {
                e.preventDefault();
                return false;
            }
        });

        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });

        // Clear console periodically
        setInterval(function() {
            console.clear();
            console.warn('%cAccess Restricted', 'color: red; font-size: 20px; font-weight: bold;');
        }, 1000);
    </script>
</body>
</html>
