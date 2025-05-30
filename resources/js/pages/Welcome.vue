<template>
    <Head title="Horizon" />
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black text-white">
        <!-- Header -->
        <header class="flex justify-between items-center px-6 py-4">
            <div class="text-xl font-bold flex items-center cursor-pointer" @click="showLanding">
                &lt;horizon/&gt;
            </div>
            <div v-if="currentView === 'landing'">
                <button @click="showLogin" class="text-sm px-4 py-1 text-white/80 hover:text-white transition duration-200 ease-in-out">
                    Log In
                </button>
                <button @click="showSignUp" class="text-sm px-4 py-1 bg-white/10 text-white rounded-full hover:bg-white/20 transition duration-200 ease-in-out">
                    Sign Up
                </button>
            </div>
            <div v-else>
                <button v-if="currentView === 'signup' || currentView === 'registrationDetails'" @click="showLogin" class="text-sm px-4 py-1 text-white/80 hover:text-white transition duration-200 ease-in-out">
                    Log In
                </button>
                <button v-if="currentView === 'login'" @click="showSignUp" class="text-sm px-4 py-1 text-white/80 hover:text-white transition duration-200 ease-in-out">
                    Sign Up
                </button>
            </div>
        </header>

        <!-- Landing Page -->
        <div v-if="currentView === 'landing'" class="container mx-auto px-6">
            <section class="py-12 md:py-20 max-w-5xl mx-auto">
        <div class="mb-16 flex flex-col md:flex-row gap-8 items-start">
            <!-- Left side: big heading text -->
            <h1 class="text-3xl md:text-4xl font-bold leading-tight md:w-1/2">
            Learn how to code<br />
            and kickstart your<br />
            programming journey<br />
            with Horizon!
            </h1>

            <!-- Right side: smaller paragraph + button below -->
            <div class="md:w-1/2 flex flex-col">
            <p class="text-sm text-white/70">
                New to programming? Horizon provides you with all you need to know, and learn thrilling concepts, built to provide an outstanding learning experience.
            </p>
            <button
                @click="showSignUp"
                class="mt-4 bg-transparent text-white py-3 rounded-full font-medium border border-white hover:bg-white/10 transition w-48"
            >
                Let's start coding
            </button>
            </div>
        </div>


              <!-- Decorative Gradient Bars - Fixed px Sizes & Alternating Alignment -->
                <div class="w-full overflow-hidden px-4">

                <!-- Bar 1: -->
                <div class="
                    h-[56px]                           <!-- Bar height -->
                    w-[full]                          <!-- Bar width-->
                    bg-gradient-to-r                   <!-- Gradient direction: left ➝ right -->
                    from-transparent to-purple-600     <!-- Gradient colors -->
                    my-4                               <!-- Vertical margin -->
                    ml-[400px]                    <!-- Shorten from left -->  
                "></div>

                <!-- Bar 2: -->
                <div class="
                    h-[56px]
                    w-[600px]
                    bg-gradient-to-r                   <!-- Gradient direction-->
                    from-transparent to-violet-400
                    rounded-r-full                     <!-- Rounded direction-->
                    my-4
                    ml-[10px]                            <!-- Push to the right -->
                "></div>

                <!-- Bar 3: -->
                <div class="
                    h-[56px]
                    w-[900px]
                    bg-gradient-to-l
                    from-transparent to-yellow-500
                    rounded-l-full
                    my-4
                    ml-auto                            <!-- Align to the right -->
                "></div>

                <!-- Bar 4:  -->
                <div class="
                    h-[56px]
                    w-[900px]
                    bg-gradient-to-r
                    from-transparent to-orange-500
                    rounded-r-full
                    my-4
                    ml-[10px]
                "></div>

                </div>






               <!-- Team Section -->
                <div class="my-16">
                <!-- Title Container -->
               <div class="flex items-center space-x-6 max-w-full">
                <!-- Left text block -->
                <div class="flex flex-col">
                    <h2 class="text-2xl md:text-3xl font-bold leading-tight">
                    Meet the team behind
                    </h2>
                    <span class="text-4xl md:text-5xl font-bold mt-2 block">Horizon</span>
                </div>

                <!-- Gradient bar next to text -->
               <div class="overflow-hidden w-[500px] h-[64px] rounded-l-full mt-[-30px] mb-[-10px] ml-[600px]">
                <div class="h-full w-[600px] bg-gradient-to-l from-transparent to-red-500 rounded-l-full"></div>
                </div>

                </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                        <img src="/Photos/Darlyn.png" alt="Team Member" class="w-full aspect-square object-cover rounded-xl mb-3">
                        <span class="font-medium">Darlyn</span>
                        <span class="text-sm text-white/70">Developer</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                        <img src="/Photos/Zach.png" alt="Team Member" class="w-full aspect-square object-cover rounded-xl mb-3">
                        <span class="font-medium">Zach</span>
                        <span class="text-sm text-white/70">CTO</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                        <img src="/Photos/Xytha.png" alt="Team Member" class="w-full aspect-square object-cover rounded-xl mb-3">
                        <span class="font-medium">Xythadel</span>
                        <span class="text-sm text-white/70">Designer</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                        <img src="/Photos/Wilson.png" alt="Team Member" class="w-full aspect-square object-cover rounded-xl mb-3">
                        <span class="font-medium">Wilson</span>
                        <span class="text-sm text-white/70">Developer</span>
                        </div>
                    </div>
                    </div>

                <!-- More bars -->
                  <div class="
                    h-[74px]
                    w-[900px]
                    bg-gradient-to-r
                    from-transparent to-blue-500
                    rounded-r-full
                    my-4
                    ml-auto
                "></div>
                <div class="
                    h-[74px]
                    w-[900px]
                    bg-gradient-to-l
                    from-transparent to-green-500
                    rounded-l-full
                    my-4
                    ml-4"></div>
            </section>
            <footer class="py-6 border-t border-white/10 flex justify-between text-sm text-white/50">
                <div>&lt;horizon/&gt;</div>
                <div>© 2025 Horizon. All rights reserved.</div>
            </footer>
        </div>

       <!-- Sign Up Page (Email and Password) -->
            <div v-if="currentView === 'signup'" class="min-h-[calc(100vh-80px)] flex items-center justify-center px-6">
            <!-- Left side: placeholder for info -->
            <div class="hidden md:flex flex-col justify-center w-1/2 pr-12 text-white">
                <!-- Add your info content here -->
                <h2 class="text-4xl font-bold mb-4">Join Us!</h2>
                <p class="text-lg text-white/70">
                Create an account to unlock full access and start your adventure with Horizon.
                </p>
            </div>

            <!-- Right side: Sign Up form -->
            <div class="bg-gray-800/30 w-full max-w-md p-8 rounded-xl backdrop-blur-sm">
                <div class="text-center mb-8">
                <h2 class="text-2xl font-bold">Sign Up</h2>
                </div>
                <form @submit.prevent="handleSignUp" class="space-y-6">
                <div>
                    <label class="text-xs text-white/50 block mb-1">Email</label>
                    <input
                    v-model="email"
                    type="email"
                    class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                    placeholder="youremail@example.com"
                    />
                </div>
                <div>
                    <label class="text-xs text-white/50 block mb-1">Password</label>
                    <input
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                    />
                    <div
                    class="text-xs text-white/50 text-right cursor-pointer mt-1"
                    @click="togglePasswordVisibility"
                    >
                    {{ showPassword ? 'Hide Password' : 'Show Password' }}
                    </div>
                </div>
                <div v-if="errorMessage" class="text-red-400 text-sm text-center">{{ errorMessage }}</div>
                <button
                    class="w-full bg-white text-black py-3 rounded-full font-medium hover:bg-white/90 transition"
                >
                    Sign Up
                </button>
                <div class="text-center text-sm my-4 text-white/50">or</div>
                <button
                    @click="continueWithGoogle"
                    type="button"
                    class="w-full bg-teal-400/20 text-teal-300 border border-teal-400/30 py-3 rounded-full font-medium hover:bg-teal-400/30 transition flex items-center justify-center"
                >
                    <span class="mr-2">Continue with</span>
                    <span class="font-bold">Google</span>
                </button>
                </form>
            </div>
            </div>

            <!-- Registration Details Page -->
            <div v-if="currentView === 'registrationDetails'" class="min-h-[calc(100vh-80px)] flex items-center justify-center px-6">
            <!-- Left side: placeholder for info -->
            <div class="hidden md:flex flex-col justify-center w-1/2 pr-12 text-white">
                <!-- Add your info content here -->
                <h2 class="text-4xl font-bold mb-4">Almost There!</h2>
                <p class="text-lg text-white/70">
                Complete your profile to personalize your experience.
                </p>
            </div>

            <!-- Right side: Profile form -->
            <div class="bg-gray-800/30 w-full max-w-md p-8 rounded-xl backdrop-blur-sm">
                <div class="text-center mb-8">
                <h2 class="text-2xl font-bold">Complete Your Profile</h2>
                </div>
                <form @submit.prevent="handleProfileSubmit" class="space-y-6">
                <div>
                    <label class="text-xs text-white/50 block mb-1">Full Name</label>
                    <input
                    v-model="name"
                    type="text"
                    class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                    placeholder="Your full name"
                    />
                </div>
                <div>
                    <label class="text-xs text-white/50 block mb-1">Birthday</label>
                    <input
                    v-model="birthday"
                    type="date"
                    class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                    />
                </div>
                <div v-if="errorMessage" class="text-red-400 text-sm text-center">{{ errorMessage }}</div>
                <button
                    class="w-full bg-white text-black py-3 rounded-full font-medium hover:bg-white/90 transition"
                >
                    Finish Registration
                </button>
                </form>
            </div>
            </div>


        <!-- Login Page -->
                <div v-if="currentView === 'login'" class="min-h-[calc(100vh-80px)] flex items-center justify-center px-6">
                <!-- Left side: placeholder for your info -->
                <div class="hidden md:flex flex-col justify-center w-1/2 pr-12 text-white">
                    <!-- Add your info content here -->
                    <h2 class="text-4xl font-bold mb-4">Welcome Back!</h2>
                    <p class="text-lg text-white/70">
                    Enter your credentials to access your account and continue your coding journey with Horizon.
                    </p>
                </div>

                <!-- Right side: Login form -->
                <div class="bg-gray-800/30 w-full max-w-md p-8 rounded-xl backdrop-blur-sm">
                    <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold">Log In</h2>
                    </div>
                    <form @submit.prevent="handleLogin" class="space-y-6">
                    <div>
                        <label class="text-xs text-white/50 block mb-1">Email</label>
                        <input
                        v-model="email"
                        type="email"
                        class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                        placeholder="youremail@example.com"
                        />
                    </div>
                    <div>
                        <label class="text-xs text-white/50 block mb-1">Password</label>
                        <input
                        v-model="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                        />
                        <div
                        class="text-xs text-white/50 text-right cursor-pointer mt-1"
                        @click="togglePasswordVisibility"
                        >
                        {{ showPassword ? 'Hide Password' : 'Show Password' }}
                        </div>
                    </div>
                    <div v-if="errorMessage" class="text-red-400 text-sm text-center">{{ errorMessage }}</div>
                    <button
                        class="w-full bg-white text-black py-3 rounded-full font-medium hover:bg-white/90 transition"
                    >
                        Log In
                    </button>
                    <div class="text-center text-sm my-4 text-white/50">or</div>
                    <button
                        @click="continueWithGoogle"
                        type="button"
                        class="w-full bg-teal-400/20 text-teal-300 border border-teal-400/30 py-3 rounded-full font-medium hover:bg-teal-400/30 transition flex items-center justify-center"
                    >
                        <span class="mr-2">Continue with</span>
                        <span class="font-bold">Google</span>
                    </button>
                    <div class="text-center text-xs text-white/50 mt-6">
                        Don't have an account?
                        <button @click="showSignUp" class="text-white underline">Sign up</button>
                    </div>
                    </form>
                </div>
                </div>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';

const currentView = ref('landing');
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const errorMessage = ref('');
const name = ref('');
const birthday = ref('');

function showSignUp() {
    currentView.value = 'signup';
    clearFields();
}
function showLogin() {
    currentView.value = 'login';
    clearFields();
}
function showLanding() {
    currentView.value = 'landing';
    clearFields();
}
function togglePasswordVisibility() {
    showPassword.value = !showPassword.value;
}
function handleLogin() {
    if (!email.value || !password.value) {
        errorMessage.value = 'Must input email or password';
        return;
    }

    errorMessage.value = '';

    router.post('/login', {
        email: email.value,
        password: password.value,
    }, {
        onError: (errors) => {
            errorMessage.value = errors.email || errors.password || 'Invalid credentials';
        },
        onSuccess: () => {
            // Redirect will happen automatically if Inertia login route does it
        }
    });
}
function handleSignUp() {
    if (!email.value || !password.value) {
        errorMessage.value = 'Must input email or password';
        return;
    }
    errorMessage.value = '';
    currentView.value = 'registrationDetails';
}
function handleProfileSubmit() {
    if (!name.value || !birthday.value) {
        errorMessage.value = 'Please fill in all fields';
        return;
    }

    errorMessage.value = '';

    router.post('/register', {
        email: email.value,
        password: password.value,
        password_confirmation: password.value,
        name: name.value,
        birthday: birthday.value,
    }, {
        onError: (errors) => {
            errorMessage.value = errors.email || errors.password || errors.name || errors.birthday || 'Something went wrong';
        },
        onSuccess: () => {
            currentView.value = 'login'; // Move to login page after successful sign-up
        }
    });
}
function continueWithGoogle() {
    window.location.href = '/auth/redirect/google';
}
function clearFields() {
    email.value = '';
    password.value = '';
    name.value = '';
    birthday.value = '';
    errorMessage.value = '';
}
</script>


<style>
body {
    background: #000;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}
</style>
