<div id="signupModal" class="hidden fixed inset-0 bg-[rgba(0,0,0,0.3)] flex items-center justify-center z-50">
    <div
        class="bg-white p-6 rounded-lg shadow-lg 
     w-11/12 sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-2/5 
     max-h-screen overflow-y-auto">
        <div class="flex justify-end mb-4">
            <p class="mr-auto self-center pl-3 font-bold">SIGN UP</p>
            <button id="closeSignupModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="signupForm" class="max-w-4xl mx-auto space-y-4 pb-2">
            <div id="signupErrors" class="text-red-600 text-sm space-y-1"></div>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="role" class="block font-medium mb-1">Role</label>
                        <select id="role" name="role"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option selected value="student">Student</option>
                            <option value="professor">Professor</option>
                            <option value="guest">Guest</option>
                        </select>
                    </div>
                    <div>
                        <label for="first_name" class="block font-medium mb-1">First Name</label>
                        <input id="first_name" name="first_name" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="last_name" class="block font-medium mb-1">Last Name</label>
                        <input id="last_name" name="last_name" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="email" class="block font-medium mb-1">Email</label>
                        <input id="email" name="email" type="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="contact_no" class="block font-medium mb-1">Contact No.</label>
                        <input id="contact_no" name="contact_no" type="number" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="local_address" class="block font-medium mb-1">Local Address</label>
                        <input id="local_address" name="local_address" type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="personal_id" class="block font-medium mb-1">ID</label>
                        <input id="personal_id" name="personal_id" type="number" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="password" class="block font-medium mb-1">Password</label>
                        <input id="password" name="password" type="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block font-medium mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>
                </div>
            </div>
            <button class="w-full mt-6 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded"
                type="submit">Sign up
            </button>
        </form>
        <button id="signupModalLink"
            class="w-full hover:bg-blue-300 hover:text-blue-700 text-blue-500 font-semibold py-2 px-4 rounded mt-2">Already
            have an account?</button>
    </div>
</div>


<script>
    const roleSelect = document.getElementById('role');
    const personalIdInput = document.getElementById('personal_id');

    roleSelect.addEventListener('change', function() {
        if (this.value === 'guest') {
            personalIdInput.disabled = true;
            personalIdInput.classList.add('bg-gray-300', 'cursor-not-allowed');
            personalIdInput.value = '';
        } else {
            personalIdInput.disabled = false;
            personalIdInput.classList.remove('bg-gray-300', 'cursor-not-allowed');
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('signupForm');
        const errorContainer = document.getElementById('signupErrors');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            errorContainer.innerHTML = '';

            try {
                const response = await fetch("{{ route('signup') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                            .value,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                if (response.status === 422) {
                    const data = await response.json();
                    const errors = data.errors;
                    Object.values(errors).forEach(messages => {
                        messages.forEach(msg => {
                            const div = document.createElement('div');
                            div.textContent = msg;
                            errorContainer.appendChild(div);
                        });
                    });
                } else if (response.ok) {
                    const data = await response.json();
                    alert(data.message || "Signup successful!");
                    form.reset();
                    document.getElementById("signupModal").classList.add("hidden");
                } else {
                    const data = await response.json();
                    errorContainer.textContent = data.message || "Something went wrong.";
                }

            } catch (error) {
                console.error(error);
                errorContainer.textContent = "A network error occurred.";
            }
        });
    });
</script>
