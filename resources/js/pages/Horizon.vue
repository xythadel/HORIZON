<!-- <script setup lang="ts">
import Bumbum from '@/components/Bumbum.vue';
</script> -->

<template>
    <html>
        <head>
            <title>Horizon</title>
        </head>

        <!-- <body>
            <div class="flex flex-wrap justify-evenly bg-slate-500">
                <Bumbum>Hiya Bumbum</Bumbum>
                <Bumbum>Hiya Babybum</Bumbum>
                <Bumbum>Hiya Boops</Bumbum>
            </div>
        </body> -->
        <body class="bg-gray-100">
            <div class="flex h-screen">
                <!-- Sidebar -->
                <aside class="w-64 bg-white p-5 text-gray-800">
                    <h2 class="text-2xl font-bold">horizon</h2>
                    <nav class="mt-5">
                        <ul>
                            <li class="py-2"><a href="#" class="hover:text-gray-400">Username</a></li>
                            <li class="py-2"><a href="#" class="hover:text-gray-400">Dashboard</a></li>
                            <li class="py-2"><a href="#" class="hover:text-gray-400">My Learning</a></li>
                            <li class="py-2"><a href="#" class="hover:text-gray-400">Settings</a></li>
                            <li class="py-2"><a href="#" class="hover:text-red-400">Logout</a></li>
                        </ul>
                    </nav>
                </aside>

                <!-- Dashboard Content -->
                <div class="text-xl">Keep Learning</div>

                <!-- <div class="relative h-[307px] w-[536px]">
                    <div class="h-[307px] w-[536px] rounded-[30px] bg-white"></div>
                    <div class="font-['Poppins'] text-xl font-normal text-[#1a1616]">Course</div>
                    <div class="w-[305px] font-['Poppins'] text-xs font-normal text-[#1a1616]">Current Module: Introduction to Vue</div>
                    <div class="font-['Poppins'] text-[25px] font-semibold text-[#1a1616]">Vue.js</div>
                    <div class="font-['Poppins'] text-xl font-medium text-[#1a1616]">Continue</div> -->

                <div class="relative h-[307px] w-[536px]">
                    <div class="h-48 w-80 rounded-[30px] bg-white"></div>
                    <div class="">Course</div>
                    <div class="">Current Module: Introduction to Vue</div>
                    <div class="">Vue.js</div>
                    <div class="">Continue</div>
                </div>
            </div>
        </body>
    </html>
</template>
