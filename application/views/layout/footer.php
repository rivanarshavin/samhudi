<script>
        const cards = document.querySelectorAll('.card');

        const layout = [
            { x: -660, s: 0.85 },
            { x: -440, s: 0.90 },
            { x: -220, s: 0.95 },
            { x: 0, s: 1.3 },
            { x: 220, s: 0.95 },
            { x: 440, s: 0.90 },
            { x: 660, s: 0.85 }
        ];

        let active = 2;

        function render() {
            cards.forEach((card, i) => {
                let pos = i - active;
                let idx = pos + 3;

                if (idx < 0) idx += 7;
                if (idx > 6) idx -= 7;

                let p = layout[idx];

                let rot = card.dataset.rot;

                if (idx === 3) {
                    rot = 0;
                }

                card.style.position = "absolute";
                card.style.top = "50%";
                card.style.left = "50%";
                card.style.transition = "all .6s ease";

                card.style.transform =
                    `translate(calc(-50% + ${p.x}px), -50%)  rotate(${rot}deg)  scale(${p.s})`;

                card.style.zIndex = idx === 3 ? 999 : 100;
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