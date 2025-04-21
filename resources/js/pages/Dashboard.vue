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
                <a href="route('dashboard')"  class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
                <a href="route('mylearning')" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
                <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Settings</a>
            </nav>
            <!-- logout -->
            <nav class="flex flex-col space-y-6 pl-20 pt-60">
                <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Logout</a>
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
    </div>
</template>

<script setup lang="ts">
import { defineProps } from 'vue';
type Course = { name: string; description: string };
interface Props {
    courses: Course[];
}

const props = defineProps<Props>();
</script>
