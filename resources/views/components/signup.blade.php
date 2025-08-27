<div id="signupModal"
    class="hidden fixed inset-0 bg-[rgba(0,0,0,0.3)] bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-3/4 md:w-1/2 lg:w-1/3 max-w-md">
        <div class="flex justify-end mb-4">
            <button id="closeSignupModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Close
            </button>
        </div>
        <form action="{{ route('signup') }}" method="POST" class="space-y-4">
            @csrf
            <input name="name" type="text" placeholder="Name"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <input name="student_id" type="number" placeholder="Student ID"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <input name="password" type="password" placeholder="Password"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <input name="password_confirmation" type="password" placeholder="Confirm Password"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>
            <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
                type="submit">Sign
                up</button>
        </form>
    </div>
</div>