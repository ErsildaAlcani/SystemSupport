// Aktivizo temën nga localStorage ose default light
function applyTheme() {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
}

// Ndërrimi i temës
function toggleTheme() {
    const isDark = document.body.classList.toggle('dark-mode');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
}

// Apliko temën kur faqja hapet
window.addEventListener('DOMContentLoaded', () => {
    applyTheme();
});

