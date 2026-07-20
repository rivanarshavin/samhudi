<script>
        const cards = document.querySelectorAll('.carousel-card');

        let active = 0;

        function render() {
            const N = cards.length;
            if (N === 0) return;

            const layoutMap = {
                '0': { x: 0, s: 1.15 },
                '1': { x: 210, s: 0.95 },
                '-1': { x: -210, s: 0.95 },
                '2': { x: 420, s: 0.90 },
                '-2': { x: -420, s: 0.90 },
                '3': { x: 630, s: 0.85 },
                '-3': { x: -630, s: 0.85 }
            };

            cards.forEach((card, i) => {
                let diff = i - active;
                
                // Circular layout wrapping calculation
                while (diff < -Math.floor(N / 2)) diff += N;
                while (diff >= Math.ceil(N / 2)) diff -= N;

                card.style.position = "absolute";
                card.style.top = "50%";
                card.style.left = "50%";
                card.style.transition = "all .6s ease";

                if (layoutMap[diff] !== undefined) {
                    let p = layoutMap[diff];
                    card.style.transform = `translate(calc(-50% + ${p.x}px), -50%) scale(${p.s})`;
                    card.style.zIndex = Math.round(p.s * 1000);
                    card.style.opacity = "1";
                    card.style.visibility = "visible";
                    card.style.pointerEvents = "auto";
                } else {
                    // Hide out of bounds cards
                    card.style.transform = `translate(-50%, -50%) scale(0.5)`;
                    card.style.zIndex = "0";
                    card.style.opacity = "0";
                    card.style.visibility = "hidden";
                    card.style.pointerEvents = "none";
                }
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