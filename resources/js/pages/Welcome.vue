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
                <button v-if="currentView === 'signup'" @click="showLogin" class="text-sm px-4 py-1 text-white/80 hover:text-white transition duration-200 ease-in-out">
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
                <div class="mb-16">
                    <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-6">
                        Learn how to code<br />and kickstart your<br />programming journey<br />with Horizon!
                    </h1>
                    <div class="flex flex-col md:flex-row gap-4 mt-8 max-w-md">
                        <div class="text-sm text-white/70">
                            <p>New to programming? Horizon provides you with all you need to know, and learn thrilling concepts, built to provide an outstanding learning experience.</p>
                        </div>
                        <div class="mt-8">
                            <button @click="showSignUp" class="bg-white text-black px-6 py-3 rounded-full font-medium hover:bg-white/90 transition">
                                Let's start coding
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Decorative Bars -->
                <div class="relative h-4 rounded-full bg-gradient-to-r from-purple-700 via-pink-500 to-purple-900 my-8 w-full"></div>
                <div class="relative h-4 rounded-full bg-gradient-to-r from-yellow-500 via-yellow-400 to-amber-600 my-8 w-full"></div>
                <div class="relative h-4 rounded-full bg-gradient-to-r from-orange-500 via-red-500 to-orange-400 my-8 w-full"></div>

                <!-- Team Section -->
                <div class="my-16">
                    <h2 class="text-2xl md:text-3xl font-bold mb-10">
                        Meet the team behind<br />
                        <span class="text-3xl md:text-4xl font-bold">Horizon</span>
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                            <span class="font-medium">Darlyn</span>
                            <span class="text-sm text-white/70">Developer</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                            <span class="font-medium">Zach</span>
                            <span class="text-sm text-white/70">CTO</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                            <span class="font-medium">Xythadel</span>
                            <span class="text-sm text-white/70">Designer</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                            <span class="font-medium">Wilson</span>
                            <span class="text-sm text-white/70">Developer</span>
                        </div>
                    </div>
                </div>

                <!-- More bars -->
                <div class="relative h-4 rounded-full bg-gradient-to-r from-blue-500 via-indigo-400 to-blue-300 my-8 w-full"></div>
                <div class="relative h-4 rounded-full bg-gradient-to-r from-emerald-500 via-green-400 to-teal-400 my-8 w-full"></div>
            </section>

            <footer class="py-6 border-t border-white/10 flex justify-between text-sm text-white/50">
                <div>&lt;horizon/&gt;</div>
                <div class="flex gap-6">
                    <span>About</span>
                    <span>Courses</span>
                </div>
                <div>© 2025 Horizon. All rights reserved.</div>
            </footer>
        </div>

        <!-- Sign Up Page -->
        <div v-if="currentView === 'signup'" class="min-h-[calc(100vh-80px)] flex items-center justify-center">
            <div class="bg-gray-800/30 w-full max-w-md p-8 rounded-lg backdrop-blur-sm">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold">Sign Up</h2>
                </div>
                <form @submit.prevent="handleSignUp" class="space-y-4">
                    <div>
                        <label class="text-xs text-white/50 block mb-1">Email</label>
                        <input 
                            v-model="email"
                            type="email"
                            class="w-full p-3 rounded-xl bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                            placeholder="youremail@example.com"
                        />
                    </div>
                    <div>
                        <label class="text-xs text-white/50 block mb-1">Password</label>
                        <input 
                            v-model="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="w-full p-3 rounded-xl bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                        />
                        <div class="text-xs text-white/50 text-right cursor-pointer mt-1" @click="togglePasswordVisibility">
                            {{ showPassword ? 'Hide Password' : 'Show Password' }}
                        </div>
                    </div>
                    <div v-if="errorMessage" class="text-red-400 text-sm text-center">
                        {{ errorMessage }}
                    </div>
                    <button class="w-full bg-white text-black py-3 rounded-xl font-medium hover:bg-white/90 transition mt-6">
                        Sign Up
                    </button>
                    <div class="text-center text-sm my-4 text-white/50">or</div>
                    <button
                        @click="continueWithGoogle"
                        type="button"
                        class="w-full bg-teal-400/20 text-teal-300 border border-teal-400/30 py-3 rounded-xl font-medium hover:bg-teal-400/30 transition flex items-center justify-center"
                    >
                        <span class="mr-2">Continue with</span>
                        <span class="font-bold">Google</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Login Page -->
        <div v-if="currentView === 'login'" class="min-h-[calc(100vh-80px)] flex items-center justify-center">
            <div class="bg-gray-800/30 w-full max-w-md p-8 rounded-lg backdrop-blur-sm">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold">Log In</h2>
                </div>
                <form @submit.prevent="handleLogin" class="space-y-4">
                    <div>
                        <label class="text-xs text-white/50 block mb-1">Email</label>
                        <input 
                            v-model="email"
                            type="email"
                            class="w-full p-3 rounded-xl bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                            placeholder="youremail@example.com"
                        />
                    </div>
                    <div>
                        <label class="text-xs text-white/50 block mb-1">Password</label>
                        <input 
                            v-model="password"
                            :type="showPassword ? 'text' : 'password'"
                            class="w-full p-3 rounded-xl bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                        />
                        <div class="text-xs text-white/50 text-right cursor-pointer mt-1" @click="togglePasswordVisibility">
                            {{ showPassword ? 'Hide Password' : 'Show Password' }}
                        </div>
                    </div>
                    <div v-if="errorMessage" class="text-red-400 text-sm text-center">
                        {{ errorMessage }}
                    </div>
                    <button class="w-full bg-white text-black py-3 rounded-xl font-medium hover:bg-white/90 transition mt-6">
                        Log In
                    </button>
                    <div class="text-center text-sm my-4 text-white/50">or</div>
                    <button
                        @click="continueWithGoogle"
                        type="button"
                        class="w-full bg-teal-400/20 text-teal-300 border border-teal-400/30 py-3 rounded-xl font-medium hover:bg-teal-400/30 transition flex items-center justify-center"
                    >
                        <span class="mr-2">Continue with</span>
                        <span class="font-bold">Google</span>
                    </button>
                    <div class="text-center text-xs text-white/50 mt-6">
                        Don't have an account? <button @click="showSignUp" class="text-white underline">Sign up</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const currentView = ref('landing');
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const errorMessage = ref('');

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
}
function handleSignUp() {
    if (!email.value || !password.value) {
        errorMessage.value = 'Must input email or password';
        return;
    }
    errorMessage.value = '';
}
function continueWithGoogle() {
    window.location.href = '/auth/google';
}
function clearFields() {
    email.value = '';
    password.value = '';
    errorMessage.value = '';
}
</script>

<style>
body {
    background: #000;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}
</style>
