<header>
    <div class="py-2 lg:mx-2 xl:mx-2">
        <x-signup />
        <x-login />
        <nav role="navigation" aria-label="Main navigation">
            <div class="flex justify-end p-2">
                <div class="lg:block hidden mr-auto">
                    <a href="{{ route('index') }}" class="lg:inline-block font-bold mx-1 p-1">
                        LOGO
                    </a>
                </div>
                @auth
                    <div class="px-2">
                        <a href="#profile"
                            class="flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                            </svg>
                            <p>{{ auth()->user()->first_name }}</p>
                        </a>
                    </div>
                    <div class="px-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full">
                                LOG OUT
                            </button>
                        </form>
                    </div>
                @else
                    <div class="sm:justify-self-end text-md px-2">
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
