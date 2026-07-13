<script>
        const cards = document.querySelectorAll('.card');

        const layout = [
            { x: -510, s: 0.85 },
            { x: -340, s: 0.90 },
            { x: -170, s: 0.95 },
            { x: 0, s: 1.15 },
            { x: 170, s: 0.95 },
            { x: 340, s: 0.90 },
            { x: 510, s: 0.85 }
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>