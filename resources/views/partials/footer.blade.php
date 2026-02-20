<!-- Footer -->
<footer class="bg-forest-dark py-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <img src="{{ asset('uploads/logo.png') }}" alt="Gamartisan Logo" class="h-10 w-auto object-contain brightness-0 invert">
                    <span class="font-display text-xl font-semibold text-cream">Gamartisan</span>
                </div>
                <p class="text-cream/70 text-sm">Crafted to Give. Made to Matter.</p>
            </div>
            <div>
                <h4 class="font-semibold text-cream mb-4">Quick Links</h4>
                <ul class="space-y-2 text-cream/70 text-sm">
                    <li><a href="{{ route('home') }}#home" class="hover:text-cream transition-colors">Home</a></li>
                    <li><a href="{{ route('home') }}#products" class="hover:text-cream transition-colors">Products</a></li>
                    <li><a href="{{ route('home') }}#about" class="hover:text-cream transition-colors">About</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-cream mb-4">Support</h4>
                <ul class="space-y-2 text-cream/70 text-sm">
                    <li><a href="#" class="hover:text-cream transition-colors">FAQs</a></li>
                    <li><a href="#" class="hover:text-cream transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-cream transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-cream mb-4">Connect</h4>
                <div class="flex space-x-3">
                    <a href="#" class="w-10 h-10 rounded-full bg-cream/10 flex items-center justify-center hover:bg-cream/20 transition-colors">
                        <svg class="w-5 h-5 text-cream" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073z" />
                            <circle cx="12" cy="12" r="3.5" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-cream/10 flex items-center justify-center hover:bg-cream/20 transition-colors">
                        <svg class="w-5 h-5 text-cream" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-cream/10 flex items-center justify-center hover:bg-cream/20 transition-colors">
                        <svg class="w-5 h-5 text-cream" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-cream/10 mt-10 pt-8 text-center">
            <p class="text-cream/50 text-sm">© {{ date('Y') }} Gamartisan. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Mobile Menu & Smooth Scroll Script -->
<script>
    document.getElementById('menuBtn').addEventListener('click', function () {
        alert('Mobile menu coming soon!');
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
</script>
