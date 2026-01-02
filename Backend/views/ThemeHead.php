<script>tailwind = { config: { darkMode: 'class' } };</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    (function() {
        const htmlElement = document.documentElement;
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            htmlElement.classList.add('dark');
        }

        window.toggleDarkMode = function() {
            htmlElement.classList.toggle('dark');
            const isDark = htmlElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);
        };
    })();
</script>