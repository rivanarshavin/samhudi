<script>
        const cards = document.querySelectorAll('.card');

        const layout = [
            { x: -630, s: 0.85 },
            { x: -420, s: 0.90 },
            { x: -210, s: 0.95 },
            { x: 0, s: 1.15 },
            { x: 210, s: 0.95 },
            { x: 420, s: 0.90 },
            { x: 630, s: 0.85 }
        ];

        let active = 2;

        function render() {
            cards.forEach((card, i) => {
                let pos = i - active;
                let idx = pos + 3;

                if (idx < 0) idx += 7;
                if (idx > 6) idx -= 7;

                let p = layout[idx];

                card.style.position = "absolute";
                card.style.top = "50%";
                card.style.left = "50%";
                card.style.transition = "all .6s ease";

                card.style.transform =
                    `translate(calc(-50% + ${p.x}px), -50%) scale(${p.s})`;

                card.style.zIndex = Math.round(p.s * 1000);
            });
        }

        cards.forEach((card, i) => {
            card.onclick = () => {
                active = i;
                render();
            };
        });

        render();
    </script>

    <script>
        function openMenu() {
            document.getElementById("sidebarMenu").style.left = "0";
            document.getElementById("menuBtn").style.display = "none";
        }

        function closeMenu() {
            document.getElementById("sidebarMenu").style.left = "-50%";
            document.getElementById("menuBtn").style.display = "block";
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const reveals = document.querySelectorAll('.reveal');
            
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -80px 0px', // Animates slightly before entering viewport fully
                threshold: 0.05
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, observerOptions);

            reveals.forEach(reveal => {
                observer.observe(reveal);
            });
        });
    </script>

<script>
        // Theme Toggle Functionality
        (function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeToggleMobile = document.getElementById('theme-toggle-mobile');
            const themeIcon = document.getElementById('theme-icon');
            const themeIconMobile = document.getElementById('theme-icon-mobile');
            
            // Apply saved theme on load
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
            document.body.setAttribute('data-theme', savedTheme);
            updateIcons(savedTheme);
            
            function updateIcons(theme) {
                const iconClass = theme === 'dark' ? 'bi-moon-stars' : 'bi-sun-fill';
                if (themeIcon) themeIcon.className = 'bi ' + iconClass + ' text-xl';
                if (themeIconMobile) themeIconMobile.className = 'bi ' + iconClass + ' text-lg';
            }
            
            function toggleTheme() {
                const currentTheme = document.body.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                document.body.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateIcons(newTheme);
            }
            
            if (themeToggle) themeToggle.addEventListener('click', toggleTheme);
            if (themeToggleMobile) themeToggleMobile.addEventListener('click', toggleTheme);
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>