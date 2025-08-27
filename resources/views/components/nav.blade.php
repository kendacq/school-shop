<header>
    <div class="py-4 px-2 lg:mx-4 xl:mx-12">
        <nav class="flex items-center justify-between flex-wrap" role="navigation" aria-label="Main navigation">
            <x-signup />
            <x-login />

            <div class="block lg:hidden">
                <button
                    class="navbar-burger flex items-center px-3 py-2 border rounded text-gray-700 border-gray-700 hover:text-blue-700 hover:border-blue-700"
                    aria-label="Toggle navigation">
                    <svg class="fill-current h-6 w-6" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <title>Menu</title>
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                    </svg>
                </button>
            </div>

            <div id="main-nav" class="w-full lg:flex items-baseline animated jackinthebox">
                <div class="lg:flex-grow text-md">
                    <a href="{{ route('store') }}"
                        class="block lg:inline-block font-bold mx-2 p-1 hover:bg-gray-300 sm:hover:bg-transparent rounded-lg
                            {{ Route::currentRouteNamed('store') ? 'text-blue-700' : 'text-gray-900 hover:text-blue-700' }}">
                        STORE
                    </a>
                </div>
                @auth
                    <div class="sm:justify-self-end text-md">
                        <a href="{{ route('profile') }}"
                            class="block lg:inline-block font-bold mx-2 p-1 hover:bg-gray-300 sm:hover:bg-transparent rounded-lg 
                                {{ Route::currentRouteNamed('profile') ? 'text-blue-700' : 'text-gray-900 hover:text-blue-700' }}">
                            PROFILE
                        </a>

                        <div class="block lg:inline-block">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                    LOG OUT
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="sm:justify-self-end text-md">
                        <button id="signupModalBtn"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                            SIGN UP
                        </button>
                        <button id="loginModalBtn"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                            LOG IN
                        </button>
                    </div>
                @endauth
            </div>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const burger = document.querySelector('.navbar-burger');
        const menu = document.getElementById('main-nav');

        if (burger && menu) {
            burger.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const openSignup = document.getElementById('signupModalBtn');
        const openLogin = document.getElementById('loginModalBtn');
        const signupModal = document.getElementById('signupModal');
        const loginModal = document.getElementById('loginModal');
        const closeSignupBtn = document.getElementById('closeSignupModal');
        const closeLoginBtn = document.getElementById('closeLoginModal');

        openSignup.addEventListener('click', () => {
            signupModal.classList.remove('hidden');
        });

        openLogin.addEventListener('click', () => {
            loginModal.classList.remove('hidden');
        });

        closeSignupBtn.addEventListener('click', () => {
            signupModal.classList.add('hidden');
        });

        closeLoginBtn.addEventListener('click', () => {
            loginModal.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (e.target === signupModal) {
                signupModal.classList.add('hidden');
            }
            if (e.target === loginModal) {
                loginModal.classList.add('hidden');
            }
        });
    });
</script>