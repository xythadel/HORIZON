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
        <body class="bg-gray-500">
            <div class="flex h-screen">
                <!-- Sidebar -->

                <div class="relative h-[1024px] w-[243px]">
                    <div class="absolute left-0 top-0 h-[1024px] w-[243px] bg-white"></div>
                    <div
                        class="absolute left-[17px] top-[138px] h-[69px] w-[69px] rounded-full bg-white shadow-[2px_2px_4px_0px_rgba(0,0,0,0.25)]"
                    ></div>
                    <div class="absolute left-[41px] top-[63px] h-[38px] w-[163px] font-['Poppins'] text-3xl font-normal text-[#2c2d2f]">horizon</div>
                    <div class="absolute left-[102px] top-[160px] h-6 w-[110px] font-['Poppins'] text-[15px] font-normal text-[#2c2d2f]">
                        Berkan Yuksel
                    </div>
                    <div class="absolute left-[41px] top-[124px] h-[0px] w-[158px] border border-[#ebebeb]"></div>
                    <div class="absolute left-[17px] top-[229px] h-[52px] w-[226px] rounded-bl-[100px] rounded-tl-[100px] bg-[#d9d9d9]"></div>
                    <div class="absolute left-[71px] top-[924px] h-[18px] w-[93px] font-['Poppins'] text-[15px] font-normal text-[#2c2d2f]">
                        Logout
                    </div>
                    <div class="absolute left-[71px] top-[244px] h-[18px] w-[86px] font-['Poppins'] text-[15px] font-normal text-[#2c2d2f]">
                        Dashboard
                    </div>
                    <div class="absolute left-[71px] top-[300px] h-[18px] w-[93px] font-['Poppins'] text-[15px] font-normal text-[#2c2d2f]">
                        My Learning
                    </div>
                    <a
                        href="/settings"
                        class="absolute left-[71px] top-[359px] h-[18px] w-[93px] font-['Poppins'] text-[15px] font-normal text-[#2c2d2f]"
                    >
                        Settings
                    </a>
                </div>

                <!-- Dashboard Content -->
                <div class="p-7 font-['Poppins'] text-3xl font-semibold text-white">Keep Learning</div>

                <!-- Vue Course Box -->
                <div class="relative mt-4 h-[325px] w-[536px]">
                    <!-- White Card Background -->
                    <div class="absolute left-0 top-[18px] flex h-[307px] w-[536px] flex-wrap rounded-[30px] bg-white shadow-lg"></div>

                    <!-- White Card Design -->
                    <div class="absolute left-[28px] top-[8px] h-1 w-[480px] rounded-tl-[30px] rounded-tr-[30px] bg-[#4ac887]"></div>
                    <div class="absolute left-[43px] top-0 h-1 w-[451px] rounded-tl-[30px] rounded-tr-[30px] bg-[#4ac887]"></div>

                    <!-- Text Content -->
                    <div class="absolute left-[116px] top-[78px] font-['Poppins'] text-xl font-normal text-[#1a1616]">Course</div>
                    <div class="absolute left-[116px] top-[110px] font-['Poppins'] text-[25px] font-semibold text-[#1a1616]">Vue.js</div>
                    <div class="absolute left-[116px] top-[170px] w-[305px] font-['Poppins'] text-xs font-normal text-[#1a1616]">
                        Current Module: Introduction to Vue
                    </div>

                    <!-- Continue Button (Same Positioning as Your Design) -->
                    <button
                        class="absolute left-[357px] top-[250px] rounded-lg bg-[#4ac887] px-4 py-2 font-['Poppins'] text-xl font-medium transition hover:bg-[#3aa86f]"
                    >
                        Continue
                    </button>
                </div>

                <!-- Laravel Course Box -->
                <div class="relative h-[324px] w-[536px]">
                    <div class="absolute left-[28px] top-[8px] h-1 w-[480px] rounded-tl-[30px] rounded-tr-[30px] bg-[#f62f28]"></div>
                    <div class="absolute left-[43px] top-0 h-1 w-[451px] rounded-tl-[30px] rounded-tr-[30px] bg-[#f62f28]"></div>
                    <div class="absolute left-0 top-[17px] h-[307px] w-[536px] rounded-[30px] bg-white"></div>
                    <div class="absolute left-[106px] top-[96px] font-['Poppins'] text-xl font-normal text-[#1a1616]">Course</div>
                    <div class="absolute left-[106px] top-[188px] w-[324px] font-['Poppins'] text-xs font-normal text-[#1a1616]">
                        a free and open-source PHP-based web framework for building web applications
                    </div>
                    <div class="absolute left-[106px] top-[128px] font-['Poppins'] text-[25px] font-semibold text-[#1a1616]">Laravel</div>
                    <div class="absolute left-[401px] top-[266px] font-['Poppins'] text-xl font-medium text-[#1a1616]">Start</div>
                </div>
            </div>
        </body>
    </html>
</template>
