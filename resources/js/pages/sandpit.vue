<template>
    <div class="flex h-screen w-full flex-wrap">
        <aside class="relative flex h-screen w-60 flex-col bg-white">
        <!-- Logo and User Info -->
        <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>

        <!-- Divider -->
        <div class="absolute left-10 top-[124px] w-40 border-t border-gray-200"></div>

        <!-- Profile Picture -->
        <div class="absolute left-4 top-[138px] h-16 w-16 rounded-full bg-white shadow-md"></div>

        <!-- Username (Dynamic) -->
        <p v-if="user" class="pl-24 pt-16 text-base font-normal text-zinc-800">
        {{ user.name }}
        </p>


        <!-- Navigation Panel -->
        <nav class="flex flex-col space-y-6 pl-20 pt-14">
            <a href="dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
            </svg>
            Dashboard
            </a>
            <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a> 
            <a href="/sandpit" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Sandpit</a> 
            <a href="/settings" class="text-base font-medium text-zinc-800 hover:text-indigo-600">Settings</a>
        </nav>

        <!-- Logout -->
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
        
        <main class="flex-1 p-12">
            <h2 class="text-xl font-bold mb-2">Sandpit</h2>

            <textarea
            v-model="code"
            rows="20"
            class="w-full border p-2 font-mono rounded-md bg-slate-800 text-white" style="scrollbar-width: thin"
            ></textarea>

            <button @click="runCode" class="mt-2 bg-blue-600 text-white px-4 py-1">
            Run
            </button>

            <h3 class="mt-4">Result:</h3>
            <iframe :srcdoc="compiledHtml" class="w-full h-[400px] border"></iframe>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const code = ref(`
<template>
    <header>
        <h1>WELCOME TO SANDPIT</h1>
    </header>
</template>

<script setup>

<\/script>

<style scoped>


header {
    line-height: 1.5;
    text-align:center;
}
</style>
`)


const compiledHtml = ref('')

const runCode = () => {
    const html = extractSFC(code.value)
    compiledHtml.value = html
}

function extractSFC(sfc) {
    const template = sfc.match(/<template>([\s\S]*?)<\/template>/)?.[1] ?? ''
    const style = sfc.match(/<style.*?>([\s\S]*?)<\/style>/)?.[1] ?? ''

    return `
        <html>
        <head>
            <style>${style}</style>
        </head>
        <body>
            <div id="app">${template}</div>
        </body>
        </html>
    `
}
</script>
