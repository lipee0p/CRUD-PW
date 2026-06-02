    </main>

    <!-- Footer -->
    <footer class="border-t border-neutral-900 bg-neutral-950 py-8 text-center text-neutral-500 text-sm mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-gold-500 font-bold">&copy; <?= date('Y') ?> Style Barber.</span>
                    <span class="hidden md:inline text-neutral-600">|</span>
                    <span>Todos os direitos reservados.</span>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="text-neutral-600">Desenvolvido com PHP, MySQL e Tailwind CSS</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Script Toggle -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            } else {
                menu.classList.add('hidden');
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            }
        }
    </script>
</body>
</html>
