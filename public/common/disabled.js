
// Browser Protection System with Custom Context Menu
 (function() {
    'use strict';

    // Check if JavaScript is enabled
    document.documentElement.classList.remove('no-js');

    // Custom Context Menu (replaces right-click blocking)
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        showCustomContextMenu(e.pageX, e.pageY);
        return false;
    });

    function showCustomContextMenu(x, y) {
        const existingMenu = document.getElementById('custom-context-menu');
        if (existingMenu) {
            existingMenu.remove();
        }

        const contextMenu = document.createElement('div');
        contextMenu.id = 'custom-context-menu';
        contextMenu.style.cssText = `
            position: fixed;
            top: ${y}px;
            left: ${x}px;
            background: hsl(var(--card));
            border: 1px solid hsl(var(--border));
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            z-index: 10000;
            padding: 8px 0;
            min-width: 200px;
            font-family: system-ui;
            font-size: 14px;
            backdrop-filter: blur(8px);
        `;

        const menuItems = [
            { icon: '🏠', text: 'Go to Home', action: () => window.location.href = '/' },
            { icon: '🔄', text: 'Refresh Page', action: () => window.location.reload() },
            { icon: '🌙', text: 'Toggle Theme', action: toggleTheme },
            { separator: true },
            { icon: '💬', text: 'Join Discussion', action: () => scrollToSection('discussions-container') },
            { icon: '👥', text: 'View Members', action: () => showNotification('Members section coming soon!') },
            { icon: '📅', text: 'Upcoming Events', action: () => scrollToSection('events') },
            { separator: true },
            { icon: '🛡️', text: 'Report Issue', action: () => showNotification('Thank you for helping us maintain quality!') },
            { icon: '❓', text: 'Help & Support', action: () => showNotification('Contact support@techcommunity.com') }
        ];

        menuItems.forEach(item => {
            if (item.separator) {
                const separator = document.createElement('div');
                separator.style.cssText = 'height: 1px; background: hsl(var(--border)); margin: 4px 0;';
                contextMenu.appendChild(separator);
            } else {
                const menuItem = document.createElement('div');
                menuItem.style.cssText = `
                    padding: 8px 16px; cursor: pointer; transition: all 0.2s ease;
                    display: flex; align-items: center; gap: 12px; color: hsl(var(--foreground));
                `;
                menuItem.innerHTML = `<span style="font-size: 16px;">${item.icon}</span><span>${item.text}</span>`;

                menuItem.addEventListener('mouseenter', function() { this.style.background = 'hsl(var(--muted))'; });
                menuItem.addEventListener('mouseleave', function() { this.style.background = 'transparent'; });
                menuItem.addEventListener('click', function() { item.action(); contextMenu.remove(); });

                contextMenu.appendChild(menuItem);
            }
        });

        document.body.appendChild(contextMenu);

        const rect = contextMenu.getBoundingClientRect();
        if (rect.right > window.innerWidth) contextMenu.style.left = (x - rect.width) + 'px';
        if (rect.bottom > window.innerHeight) contextMenu.style.top = (y - rect.height) + 'px';

        setTimeout(() => {
            document.addEventListener('click', function closeMenu() {
                contextMenu.remove();
                document.removeEventListener('click', closeMenu);
            });
        }, 100);
    }

    // Disable text selection
    document.addEventListener('selectstart', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable drag
    document.addEventListener('dragstart', function(e) {
        e.preventDefault();
        return false;
    });

    // Disable common keyboard shortcuts INCLUDING Ctrl+U
    document.addEventListener('keydown', function(e) {
        if (e.keyCode === 123 || // F12
            (e.ctrlKey && e.shiftKey && e.keyCode === 73) || // Ctrl+Shift+I
            (e.ctrlKey && e.shiftKey && e.keyCode === 74) || // Ctrl+Shift+J
            (e.ctrlKey && e.keyCode === 85) || // Ctrl+U (VIEW SOURCE)
            (e.ctrlKey && e.keyCode === 83) || // Ctrl+S
            (e.ctrlKey && e.keyCode === 65) || // Ctrl+A
            (e.ctrlKey && e.keyCode === 80)) { // Ctrl+P

            e.preventDefault();
            showWarning('This action is disabled for content protection');
            return false;
        }
    });

    // DevTools detection
    let devtools = { open: false, orientation: null };
    const threshold = 160;

    function detectDevTools() {
        if (window.outerHeight - window.innerHeight > threshold ||
            window.outerWidth - window.innerWidth > threshold) {
            if (!devtools.open) {
                devtools.open = true;
                handleDevToolsOpen();
            }
        } else {
            devtools.open = false;
        }
    }

    function handleDevToolsOpen() {
        if (confirm('Developer tools detected. Redirecting to home page.')) {
            window.location.href = '/';
        } else {
            window.location.href = '/';
        }
    }

    // Console detection
    let consoleChecker = setInterval(function() {
        if (window.console && (window.console.firebug || window.console.exception && window.console.table)) {
            clearInterval(consoleChecker);
            window.location.href = '/console-detected';
        }
    }, 1000);

    // Monitor window resize
    window.addEventListener('resize', detectDevTools);
    detectDevTools();

    // Console warning
    console.clear();
    console.warn('%cSTOP!', 'color: red; font-size: 50px; font-weight: bold;');
    console.warn('%cThis is a browser feature intended for developers. Unauthorized access may violate terms of service.', 'color: red; font-size: 16px;');

    // Clear console periodically
    setInterval(function() {
        console.clear();
    }, 3000);

    // Print screen detection
    document.addEventListener('keyup', function(e) {
        if (e.keyCode === 44) {
            showWarning('Screenshot disabled');
        }
    });

    // Helper functions
    function toggleTheme() {
        const html = document.documentElement;
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            html.classList.add('light');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.remove('light');
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
        showNotification('Theme changed successfully!');
    }

    function scrollToSection(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
            showNotification('Scrolled to section');
        }
    }

    function showWarning(message) {
        const warning = document.createElement('div');
        warning.style.cssText = `
            position: fixed; top: 20px; right: 20px; background: #ef4444; color: white;
            padding: 12px 20px; border-radius: 8px; z-index: 10000; font-family: system-ui;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        `;
        warning.textContent = message;
        document.body.appendChild(warning);
        setTimeout(() => warning.remove(), 3000);
    }

    function showNotification(message) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed; top: 20px; right: 20px; background: hsl(var(--primary));
            color: hsl(var(--primary-foreground)); padding: 12px 20px; border-radius: 8px;
            z-index: 10001; font-family: system-ui; box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

})();
