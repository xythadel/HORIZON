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
                <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
                <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
                <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Settings</a>
            </nav>
            <!-- logout -->
            <nav class="flex flex-col space-y-6 pl-20 pt-60">
                <a href="#" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Logout</a>
            </nav>
        </aside>
    </div>
</template>
