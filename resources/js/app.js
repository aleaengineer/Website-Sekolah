document.querySelectorAll('[role="alert"]').forEach((alert) => {
    window.setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';

        window.setTimeout(() => alert.remove(), 500);
    }, 3000);
});
