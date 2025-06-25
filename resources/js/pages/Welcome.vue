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
                        <span class="text-sm text-white/70">Developer</span>
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

       <!-- Sign Up Page (Correct Layout with single form) -->
        <div v-if="currentView === 'signup'" class="min-h-screen flex items-center justify-center bg-black relative overflow-hidden px-6">
        <!-- Background Blur/Gradient -->
        <div class="absolute w-full h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-orange-400 opacity-40 blur-3xl z-0"></div>

        <form @submit.prevent="handleSignUp" class="relative z-10 w-full max-w-6xl flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0 md:space-x-6">
            
            <!-- Left: Pill paragraphs in a rectangular container -->
            <div 
            class="bg-white/5 p-10 rounded-3xl backdrop-blur-md shadow-2xl text-white flex flex-col gap-6 w-full md:w-full max-w-md min-h-[500px] md:min-h-[510px] justify-center"
            >
            <!-- Pill 1: Circle on the right -->
            <div 
                class="flex items-center bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-full shadow-lg"
                style="
                padding: 16px 40px;           /* Top-bottom: 16px, Left-right: 40px */
                width: 100%;                  /* Stretch to container width */
                max-width: 400px;             /* You can adjust this */
                height: 120px;                 /* Pill height */
                justify-content: flex-start; /* Align content horizontally */
                gap: 25px;                    /* Space between circle and text */
                "
            >
                <!-- Text on the left -->
                <p class="text-3xl font-bold text-black">
                Are you ready?
                </p>

                <!-- Circle pushed to the right -->
                <div 
                class="bg-yellow-400 rounded-full ml-auto"
                style="
                    width: 125px;               /* Circle width */
                    height: 100px;              /* Circle height */
                "
                ></div>
            </div>

            <!-- Pill 2 -->
            <div class="flex items-center gap-4 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-full px-6 py-4 shadow-lg"
                style="
                padding: 16px 30px;           /* Top-bottom: 16px, Left-right: 40px */
                width: 100%;                  /* Stretch to container width */
                max-width: 400px;             /* You can adjust this */
                height: 120px;                 /* Pill height */
                justify-content: flex-start; /* Align content horizontally */
                gap: 9px;                    /* Space between circle and text */
                "
            >
                <div 
                class="bg-violet-600 rounded-full"
                style="
                    width: 125px;               /* Circle width */
                    height: 100px;              /* Circle height */
                    order: 0;                   /* Left side */
                "
                ></div>
                <p class="text-2xl font-bold text-black"
                style="order: 1;"  
                >To learn how to code?</p>
            </div>

            <!-- Pill 3 -->
            <div class="flex items-center gap-4 bg-gradient-to-r from-red-500 to-orange-400 text-white rounded-full px-6 py-4 shadow-lg"
                style="
                padding: 16px 20px;           /* Top-bottom: 16px, Left-right: 40px */
                width: 100%;                  /* Stretch to container width */
                max-width: 400px;             /* You can adjust this */
                height: 120px;                 /* Pill height */
                justify-content: flex-start; /* Align content horizontally */
                gap: 0px;                    /* Space between circle and text */
                "
            >
                <p class="text-2xl font-bold text-black">
                Let's get started!
                </p>
                <div 
                class="bg-red-600 rounded-full ml-auto flex items-center justify-center text-black text-5xl font-bold"
                style="
                    width: 100px;               /* Circle width */
                    height: 100px;              /* Circle height */
                ">
                &#8594;  <!-- Unicode right arrow → -->
                </div>
            </div>
            </div>

   <!-- Right: Sign Up Form -->
            <div class="p-8 text-white w-full max-w-md mt-12 md:mt-0">
            <div class="text-left mb-6">
                <h2 class="text-3xl font-bold">Sign Up</h2>
            </div>
            <form @submit.prevent="handleSignUp" class="space-y-6">
            <!-- Full Name -->
            <div>
                <label class="text-xs text-white/70 block mb-2">Full Name</label>
                <input
                v-model="fullName"
                type="text"
                class="w-full px-4 py-3 rounded-full bg-black/30 border border-white/10 placeholder-white/50 text-white focus:border-purple-500 focus:outline-none"
                placeholder="Your full name"
                />
            </div>

            <!-- Birthday -->
            <div>
                <label class="text-xs text-white/70 block mb-2">Birthday</label>
                <input
                v-model="birthday"
                type="date"
                class="w-full px-4 py-3 rounded-full bg-black/30 border border-white/10 placeholder-white/50 text-white focus:border-purple-500 focus:outline-none"
                />
            </div>

            <!-- Email -->
            <div>
                <label class="text-xs text-white/70 block mb-2">Email</label>
                <input
                v-model="email"
                type="email"
                class="w-full px-4 py-3 rounded-full bg-black/30 border border-white/10 placeholder-white/50 text-white focus:border-purple-500 focus:outline-none"
                placeholder="youremail@example.com"
                />
            </div>

            <!-- Password -->
            <div>
                <label class="text-xs text-white/70 block mb-2">Password</label>
                <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                class="w-full px-4 py-3 rounded-full bg-black/30 border border-white/10 placeholder-white/50 text-white focus:border-purple-500 focus:outline-none"
                placeholder="Enter your password"
                />
                <div
                class="text-xs text-white/50 text-right cursor-pointer mt-1 hover:underline"
                @click="togglePasswordVisibility"
                >
                {{ showPassword ? 'Hide Password' : 'Show Password' }}
                </div>
            </div>

            <!-- Verify Password -->
            <div>
                <label class="text-xs text-white/70 block mb-2">Verify Password</label>
                <input
                v-model="verifyPassword"
                :type="showPassword ? 'text' : 'password'"
                class="w-full px-4 py-3 rounded-full bg-black/30 border border-white/10 placeholder-white/50 text-white focus:border-purple-500 focus:outline-none"
                placeholder="Re-enter your password"
                />
                <div
                class="text-xs text-white/50 text-right cursor-pointer mt-1 hover:underline"
                @click="togglePasswordVisibility"
                >
                {{ showPassword ? 'Hide Password' : 'Show Password' }}
                </div>
            </div>

            <div v-if="errorMessage" class="text-red-400 text-sm text-center">{{ errorMessage }}</div>

            <button
                type="submit"
                class="w-full bg-white text-black py-3 rounded-full font-semibold hover:bg-white/90 transition"
            >
                Sign Up
            </button>

                <div class="flex items-center my-4">
                <div class="flex-grow h-px bg-white/20"></div>
                <span class="px-4 text-white/60 text-sm">or</span>
                <div class="flex-grow h-px bg-white/20"></div>
                </div>

                <button
                @click="continueWithGoogle"
                type="button"
                class="w-full py-3 rounded-full bg-blue-400 text-white font-semibold shadow-md hover:opacity-90 transition flex items-center justify-center"
                >
                <span class="mr-2">Continue with</span>
                <span class="font-bold">Google</span>
                </button>
            </form>
            </div>


            </form>
            </div>



       <!-- Login Page -->
        <div v-if="currentView === 'login'" class="min-h-screen flex items-center justify-center bg-black relative overflow-hidden px-6">
        <!-- Background Gradient Blur -->
        <div class="absolute w-full h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-orange-400 opacity-40 blur-3xl z-0"></div>

        <!-- Main container -->
        <div class="relative z-10 w-full max-w-6xl flex flex-col md:flex-row items-center justify-between">
            
            <!-- Left: Decorative Pill Design -->
            <div 
            class="bg-white/5 p-10 rounded-3xl backdrop-blur-md shadow-2xl text-white flex flex-col gap-6 w-full md:w-full max-w-md min-h-[500px] md:min-h-[510px] justify-center"
            >
            <!-- Pill 1 -->
            <div 
                class="flex items-center bg-gradient-to-r from-blue-600 to-indigo-500 text-white rounded-full shadow-lg"
                style="padding: 16px 40px; width: 100%; max-width: 400px; height: 120px; justify-content: flex-start; gap: 25px;"
            >
                <p class="text-3xl font-bold text-black">
                Welcome Back!
                </p>
                <div class="bg-pink-400 rounded-full ml-auto" style="width: 125px; height: 100px;"></div>
            </div>

            <!-- Pill 2 -->
            <div 
                class="flex items-center gap-4 bg-gradient-to-r from-purple-600 to-blue-400 text-white rounded-full px-6 py-4 shadow-lg"
                style="padding: 16px 30px; width: 100%; max-width: 400px; height: 120px; justify-content: flex-start; gap: 9px;"
            >
                <div class="bg-purple-700 rounded-full" style="width: 125px; height: 100px; order: 0;"></div>
                <p class="text-2xl font-bold text-black" style="order: 1;">
                Enter your credentials
                </p>
            </div>

            <!-- Pill 3 -->
            <div 
                class="flex items-center gap-4 bg-gradient-to-r from-red-500 to-orange-400 text-white rounded-full px-6 py-4 shadow-lg"
                style="padding: 16px 20px; width: 100%; max-width: 400px; height: 120px; justify-content: flex-start; gap: 0px;"
            >
                <p class="text-2xl font-bold text-black">
                Let’s sign in!
                </p>
                <div 
                class="bg-red-600 rounded-full ml-auto flex items-center justify-center text-black text-5xl font-bold"
                style="width: 100px; height: 100px;"
                >
                &#8594;
                </div>
            </div>
            </div>

            <!-- Right: Login Form (Styled the Same as Sign-Up) -->
            <div class="p-8 text-white w-full max-w-md mt-12 md:mt-0">
            <div class="text-center mb-8 text-white">
                <h2 class="text-3xl font-bold">Log In</h2>
                <p class="text-white/60 mt-1">Enter your credentials to continue.</p>
            </div>
            <form @submit.prevent="handleLogin" class="space-y-6">
                <!-- Email -->
                <div>
                <label class="text-xs text-white/50 block mb-1">Email</label>
                <input
                    v-model="email"
                    type="email"
                    class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none text-white"
                    placeholder="youremail@example.com"
                />
                </div>

                <!-- Password -->
                <div>
                <label class="text-xs text-white/50 block mb-1">Password</label>
                <input
                    v-model="password"
                    :type="showPassword ? 'text' : 'password'"
                    class="w-full p-3 rounded-full bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none text-white"
                />
                <div
                    class="text-xs text-white/50 text-right cursor-pointer mt-1"
                    @click="togglePasswordVisibility"
                >
                    {{ showPassword ? 'Hide Password' : 'Show Password' }}
                </div>
                </div>

                <!-- Error -->
                <div v-if="errorMessage" class="text-red-400 text-sm text-center">{{ errorMessage }}</div>

                <!-- Log In Button -->
                <button
                class="w-full bg-white text-black py-3 rounded-full font-medium hover:bg-white/90 transition"
                >
                Log In
                </button>

                <!-- Divider -->
                <div class="text-center text-sm my-4 text-white/50">or</div>

                <!-- Google Sign In -->
                <button
                @click="continueWithGoogle"
                type="button"
                class="w-full bg-teal-400/20 text-teal-300 border border-teal-400/30 py-3 rounded-full font-medium hover:bg-teal-400/30 transition flex items-center justify-center"
                >
                <span class="mr-2">Continue with</span>
                <span class="font-bold">Google</span>
                </button>

                <!-- Sign Up Redirect -->
                <div class="text-center text-xs text-white/50 mt-6">
                Don't have an account?
                <button @click="showSignUp" class="text-white underline">Sign up</button>
                </div>
            </form>
            </div>
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
const verifyPassword = ref('');
const showPassword = ref(false);
const errorMessage = ref('');
const fullName = ref('');
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
            // Redirect happens automatically if Inertia login route does it
        }
    });
}


function handleSignUp() {
    // Validate all signup fields in one go
    if (!fullName.value || !birthday.value || !email.value || !password.value || !verifyPassword.value) {
        errorMessage.value = 'Please fill in all fields';
        return;
    }

    if (password.value !== verifyPassword.value) {
        errorMessage.value = 'Passwords do not match';
        return;
    }

    errorMessage.value = '';

    router.post('/register', {
        name: fullName.value,
        birthday: birthday.value,
        email: email.value,
        password: password.value,
        password_confirmation: verifyPassword.value,
    }, {
        onError: (errors) => {
            errorMessage.value = errors.email || errors.password || errors.name || errors.birthday || 'Something went wrong';
        },
        onSuccess: () => {
            currentView.value = 'login'; // Redirect to login after successful signup
            clearFields();
        }
    });
}

function continueWithGoogle() {
    window.location.href = '/auth/redirect/google';
}

function clearFields() {
    email.value = '';
    password.value = '';
    verifyPassword.value = ''; // clear new field too
    fullName.value = '';
    birthday.value = '';
    errorMessage.value = '';
}
</script>