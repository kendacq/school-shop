<div id="loginModal"
    class="hidden fixed inset-0 bg-[rgba(0,0,0,0.3)] bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-3/4 md:w-1/2 lg:w-1/3 max-w-md">
        <div class="flex justify-end mb-4">
            <p class="mr-auto self-center pl-3 font-bold">LOG IN</p>
            <button id="closeLoginModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route(name: 'login') }}" method="POST" class="space-y-4 pb-2">
            @csrf
            <input name="personal_id" type="text" placeholder="ID"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <input name="password" type="password" placeholder="Password"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                Log In
            </button>
        </form>
        <button id="loginModalLink"
            class="w-full hover:bg-green-200 hover:text-green-900 text-green-700 font-semibold py-2 px-4 rounded">No
            account? Sign up here!</button>
    </div>
</div>
