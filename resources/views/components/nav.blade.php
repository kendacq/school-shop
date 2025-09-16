<header>
    <div class="py-2 lg:mx-2 xl:mx-2">
        <x-signup />
        <x-login />
        <nav role="navigation" aria-label="Main navigation" class="bg-white shadow-md">
            <div class="container mx-auto flex flex-wrap items-center justify-between p-4">
                <div class="mr-auto">
                    <a href="{{ route('index') }}" class="lg:hidden text-lg font-bold mx-1 p-1 hover:text-gray-700">
                        LOGO
                    </a>
                    <a href="{{ route('index') }}"
                        class="hidden lg:inline-block text-xl font-bold mx-1 p-1 hover:text-gray-700">
                        LOGO
                    </a>
                </div>
                @auth
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('cart.index') }}"
                            class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white font-bold h-10 min-w-[45px] px-4 rounded-sm justify-center transition"
                            aria-label="View cart">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
                                aria-hidden="true" role="img">
                                <title>Cart</title>
                                <path d="M6 6h15l-1.4 7.2a2 2 0 0 1-2 1.6H9.4L7.6 6H6z" />
                                <circle cx="10" cy="19" r="1.4" />
                                <circle cx="18" cy="19" r="1.4" />
                            </svg>
                        </a>
                        <a href="#profile"
                            class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white font-bold h-10 min-w-[100px] px-4 rounded-sm justify-center transition">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" role="img" aria-label="Profile">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 8-4 8-4s8 0 8 4" />
                            </svg>
                            <p>{{ auth()->user()->first_name }}</p>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full transition">
                                LOG OUT
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex flex-wrap items-center gap-2">
                        <button id="signupModalBtn"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition">
                            SIGN UP
                        </button>
                        <button id="loginModalBtn"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full transition">
                            LOG IN
                        </button>
                    </div>
                @endauth
            </div>
        </nav>
    </div>
</header>

@guest
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openSignup = document.getElementById('signupModalBtn');
            const openLogin = document.getElementById('loginModalBtn');
            const openSignupLink = document.getElementById('signupModalLink');
            const openLoginLink = document.getElementById('loginModalLink');
            const signupModal = document.getElementById('signupModal');
            const loginModal = document.getElementById('loginModal');
            const closeSignupBtn = document.getElementById('closeSignupModal');
            const closeLoginBtn = document.getElementById('closeLoginModal');

            openSignup.addEventListener('click', () => {
                signupModal.classList.remove('hidden');
            });

            openSignupLink.addEventListener('click', () => {
                signupModal.classList.add('hidden');
                loginModal.classList.remove('hidden');
            });

            openLogin.addEventListener('click', () => {
                loginModal.classList.remove('hidden');
            });

            openLoginLink.addEventListener('click', () => {
                signupModal.classList.remove('hidden');
                loginModal.classList.add('hidden');
            });

            closeSignupBtn.addEventListener('click', () => {
                signupModal.classList.add('hidden');
            });

            closeLoginBtn.addEventListener('click', () => {
                loginModal.classList.add('hidden');
            });
        });
    </script>
@endguest
