<template>
    <div class="flex h-screen w-full flex-wrap bg-zinc-800">
        <!-- sidebar -->
        <aside class="relative flex h-screen w-60 flex-col bg-white">
            <!-- logo -->
            <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
            <!-- Divider -->
            <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>
            <!-- profile -->
            <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md"></div>
            <!-- username -->
            <p class="pl-24 pt-16 text-base font-normal text-zinc-800">Berkan Yuksel</p>

            <!-- navigation -->
            <nav class="flex flex-col space-y-6 pl-20 pt-14">
                <a href="dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
                <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
                <a href="/settings" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Settings</a>
            </nav>

            <!-- logout with confirmation -->
            <nav class="flex flex-col space-y-6 pl-20 pt-60">
                <a
                    href="#"
                    @click.prevent="showLogoutModal = true"
                    class="text-base font-normal text-zinc-800 hover:text-indigo-600"
                >
                    Logout
                </a>
            </nav>
        </aside>

        <main class="flex h-screen flex-1">
            <div class="flex-1 bg-gradient-to-b from-zinc-800 via-zinc-800 to-indigo-700"></div>

            <!-- Main Banner -->
            <div
                class="absolute left-[439px] top-[30px] h-32 w-[912px] rounded-bl-[80px] rounded-tl-[80px] bg-gradient-to-r from-indigo-800 to-zinc-800"
            ></div>

            <!-- Banner Text -->
            <h1 class="absolute left-[515px] top-[60px] w-[856px] text-2xl font-medium text-white">
                Ready to code? Start learning Vue.js or Laravel today<br />and track your growth on our Progress Board!
            </h1>

            <!-- Course Selection Banner -->
            <div
                class="absolute left-[242px] top-[170px] h-24 w-[700px] rounded-br-[80px] rounded-tr-[80px] bg-gradient-to-r from-zinc-800 to-yellow-600"
            ></div>

            <!-- Course Selection Text -->
            <p class="absolute left-[280px] top-[190px] w-[650px] text-xl font-medium text-white">
                Choose your course and start your coding adventure. <br />
                Your journey begins now!
            </p>

            <!-- COURSES -->
            <div class="absolute top-80 ml-12 flex w-[1500px] flex-wrap gap-5">
                <div class="h-72 w-[500px] rounded-[30px] bg-white" v-for="course in props.courses" v-bind:key="course.name">
                    <h2 class="text-xl font-normal text-stone-900">Course</h2>
                    <h3 class="text-2xl font-semibold text-stone-900">{{ course.name }}</h3>
                    <p class="w-80 text-xs font-normal text-stone-900">
                        {{ course.description }}
                    </p>
                    <button class="text-xl font-medium text-stone-900 hover:text-green-500">Start</button>
                </div>
            </div>
        </main>

        <!-- Logout Confirmation Modal -->
        <div
            v-if="showLogoutModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm text-center text-gray-800">
                <h2 class="text-xl font-semibold mb-4">Are you sure?</h2>
                <p class="mb-6">Do you really want to logout from your account?</p>
                <div class="flex justify-center space-x-4">
                    <button
                        @click="confirmLogout"
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                    >
                        Yes, Logout
                    </button>
                    <button
                        @click="showLogoutModal = false"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { defineProps } from 'vue';

type Course = { name: string; description: string };
interface Props {
    courses: Course[];
}

const props = defineProps<Props>();
const showLogoutModal = ref(false)

function confirmLogout() {
    router.post('/logout')
}
</script>
