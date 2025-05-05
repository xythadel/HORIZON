<template>
    <Head title="Horizon" />
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black text-white">
        <!-- Header -->
        <header class="flex justify-between items-center px-6 py-4">
            <div class="text-xl font-bold flex items-center">
                <button @click="Welcome" class="text-white">&lt;horizon/&gt;</button>
            </div>
            <div v-if="currentView === 'landing'">
                <button @click="Login" class="text-sm px-4 py-1 text-white/80 hover:text-white">Log In</button>
                <button @click="showSignUp" class="text-sm px-4 py-1 bg-white/10 text-white rounded-full hover:bg-white/20">Sign Up</button>
            </div>
            <div v-else>
                <button v-if="currentView === 'signup'" @click="showLogin" class="text-sm px-4 py-1 text-white/80 hover:text-white">Log In</button>
                <button v-if="currentView === 'login'" @click="showSignUp" class="text-sm px-4 py-1 text-white/80 hover:text-white">Sign Up</button>
            </div>
        </header>

        <!-- Landing Page -->
        <div v-if="currentView === 'landing'" class="container mx-auto px-6">
            <!-- Hero Section -->
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
                
                <!-- Colorful bars -->
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
                            <span class="font-medium">Sarah</span>
                            <span class="text-sm text-white/70">Developer</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                            <span class="font-medium">Alejandro</span>
                            <span class="text-sm text-white/70">CTO</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                            <span class="font-medium">Naomi</span>
                            <span class="text-sm text-white/70">Designer</span>
                        </div>
                        <div class="bg-gray-800 rounded-lg p-4 aspect-square flex flex-col justify-end">
                            <span class="font-medium">Wilson</span>
                            <span class="text-sm text-white/70">Developer</span>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom colorful bar -->
                <div class="relative h-4 rounded-full bg-gradient-to-r from-blue-500 via-indigo-400 to-blue-300 my-8 w-full"></div>
                <div class="relative h-4 rounded-full bg-gradient-to-r from-emerald-500 via-green-400 to-teal-400 my-8 w-full"></div>
            </section>
            
            <!-- Footer -->
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
                
                <!-- Side bubbles -->
                <div class="absolute -left-4 top-1/4 flex flex-col gap-4">
                    <div class="flex items-center bg-gradient-to-r from-teal-400 to-cyan-300 rounded-full p-2 pl-4 pr-6">
                        <span class="mr-2">Are you ready</span>
                        <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center">
                            <span>?</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center bg-gradient-to-r from-indigo-700 to-purple-600 rounded-full p-2 pl-4 pr-6">
                        <span class="mr-2">To learn code?</span>
                        <div class="w-8 h-8 rounded-full bg-indigo-900 flex items-center justify-center">
                            <span>👨‍💻</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center bg-gradient-to-r from-orange-600 to-red-500 rounded-full p-2 pl-4 pr-6">
                        <span class="mr-2">Let's get started</span>
                        <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center">
                            <span>→</span>
                        </div>
                    </div>
                </div>
                
                <form @submit.prevent class="space-y-4">
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
                            type="password" 
                            class="w-full p-3 rounded-xl bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                        />
                    </div>
                    
                    <button class="w-full bg-white text-black py-3 rounded-xl font-medium hover:bg-white/90 transition mt-6">
                        Sign Up
                    </button>
                    
                    <div class="text-center text-sm my-4 text-white/50">or</div>
                    
                    <button class="w-full bg-teal-400/20 text-teal-300 border border-teal-400/30 py-3 rounded-xl font-medium hover:bg-teal-400/30 transition flex items-center justify-center">
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
                
                <!-- Side bubbles -->
                <div class="absolute -left-4 top-1/4 flex flex-col gap-4">
                    <div class="flex items-center bg-gradient-to-r from-teal-400 to-cyan-300 rounded-full p-2 pl-4 pr-6">
                        <span class="mr-2">Welcome back!</span>
                        <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center">
                            <span>👋</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center bg-gradient-to-r from-green-400 to-teal-500 rounded-full p-2 pl-4 pr-6">
                        <span class="mr-2">Let's continue learning</span>
                        <div class="w-8 h-8 rounded-full bg-purple-800 flex items-center justify-center">
                            <span>👨‍💻</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center bg-gradient-to-r from-orange-600 to-red-500 rounded-full p-2 pl-4 pr-6">
                        <span class="mr-2">Let's get started</span>
                        <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center">
                            <span>→</span>
                        </div>
                    </div>
                </div>
                
                <form @submit.prevent class="space-y-4">
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
                            type="password" 
                            class="w-full p-3 rounded-xl bg-black/30 border border-white/10 focus:border-purple-500 focus:outline-none"
                        />
                    </div>
                    
                    <button class="w-full bg-white text-black py-3 rounded-xl font-medium hover:bg-white/90 transition mt-6">
                        Log In
                    </button>
                    
                    <div class="text-center text-sm my-4 text-white/50">or</div>
                    
                    <button class="w-full bg-teal-400/20 text-teal-300 border border-teal-400/30 py-3 rounded-xl font-medium hover:bg-teal-400/30 transition flex items-center justify-center">
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

<style>
/* Add any additional styles here */
body {
    background: #000;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}
</style>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

// State management

function Welcome() {
    // Using Inertia router
    router.visit(route('welcome'));
}  
    const currentView = ref('landing'); // 'landing', 'signup', or 'login'
const email = ref('');
const password = ref('');

// Functions to change views
function showSignUp() {
    currentView.value = 'signup';
}

function showLogin() {
    currentView.value = 'login';
}

function showLanding() {
    currentView.value = 'landing';
}
</script>