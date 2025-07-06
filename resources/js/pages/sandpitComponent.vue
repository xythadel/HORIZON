<template>
    <div class="flex h-screen w-full flex-wrap">
        <!-- Main Area -->
        <main class="flex-1 p-6 overflow-y-auto">
        <h2 class="text-2xl font-bold mb-4">Sandpit</h2>

        <!-- Language Select -->
        <div class="mb-4">
            <label class="mr-2 font-medium">Language:</label>
            <select v-model="selectedLanguage" class="border p-1 rounded">
            <option value="vue">Vue.js</option>
            <option value="laravel">Laravel Blade</option>
            </select>
        </div>

        <!-- Tabs -->
        <div class="mb-2 flex space-x-4 border-b">
            <button
            v-for="tab in visibleTabs"
            :key="tab"
            @click="activeTab = tab"
            :class="[
                'py-1 px-4 border-b-2',
                activeTab === tab ? 'border-indigo-500 font-semibold' : 'border-transparent',
            ]"
            >
            {{ tab }}
            </button>
        </div>

        <!-- Code Area -->
        <textarea
            v-model="codeSections[activeTab]"
            rows="12"
            class="w-full border rounded-md p-2 font-mono bg-slate-800 text-white"
            style="scrollbar-width: thin;"
        ></textarea>

        <!-- Run -->
        <div class="mt-3">
            <button @click="runCode" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Run
            </button>
        </div>

        <!-- Error -->
        <div v-if="error" class="mt-4 text-red-500 font-mono bg-red-100 p-2 rounded">
            {{ error }}
        </div>

        <!-- Result -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Result:</h3>
            <iframe :srcdoc="compiledHtml" class="w-full h-[300px] border rounded-md"></iframe>
        </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'

    const user = ref({ name: 'John' })
    const selectedLanguage = ref('vue')
    const activeTab = ref('template')

    const tabs = {
    vue: ['template', 'script', 'style'],
    laravel: ['template', 'web.php', 'style']
    }

    const visibleTabs = computed(() => tabs[selectedLanguage.value])

    const codeSections = ref({
    template: '',
    script: '',
    style: '',
    'web.php': ''
    })

    const compiledHtml = ref('')
    const error = ref('')
    watch(selectedLanguage, (lang) => {
    if (lang === 'laravel') {
        activeTab.value = 'template'
        codeSections.value.template = `<div>
    <h1>Hello, {{ name }}</h1>

    @if(isAdmin)
        <p>Welcome back, administrator!</p>
    @else
        <p>You are logged in as a regular user.</p>
    @endif

    <ul>
        @foreach($tasks as $task)
        <li>{{ task }}</li>
        @endforeach
    </ul>
    </div>`
        codeSections.value['web.php'] = `$name = "Jane";
    $isAdmin = true;
    $tasks = ["Write docs", "Fix bug", "Deploy"];`
        codeSections.value.style = `body { font-family: sans-serif; padding: 20px; }`
    } else {
        activeTab.value = 'template'
        codeSections.value.template = `<header>\n  <h1>WELCOME TO SANDPIT</h1>\n</header>`
        codeSections.value.script = `// JS logic here`
        codeSections.value.style = `header { text-align: center; }`
    }
    })

    function parseWebPhp(phpCode) {
    const lines = phpCode.split('\n')
    const data = {}
    for (let line of lines) {
        line = line.trim()
        if (line.startsWith('$')) {
        let [key, value] = line.split('=')
        key = key.replace('$', '').trim()
        value = value.trim().replace(/;$/, '')

        try {

            if (value.startsWith('"') || value.startsWith("'")) {
            data[key] = value.replace(/^["']|["']$/g, '')
            } else if (value.startsWith('[')) {
            data[key] = eval(value)
            } else {
            data[key] = eval(value)
            }
        } catch (e) {
            console.warn('Failed parsing:', line)
        }
        }
    }
    return data
    }
    function runCode() {
    error.value = ''

    try {
        if (selectedLanguage.value === 'vue') {
            compiledHtml.value = `
                <html>
                <head>
                <style>${codeSections.value.style}</style>
                </head>
                <body>
                <div id="app">
                    ${codeSections.value.template}
                </div>
                <script>
                    ${codeSections.value.script}
                <\/script>
                </body>
                </html>
            `
            } else if (selectedLanguage.value === 'laravel') {
            const data = parseWebPhp(codeSections.value['web.php'])
            let rendered = codeSections.value.template
            rendered = rendered.replace(/\{\{\s*(\w+)\s*\}\}/g, (_, key) => data[key] ?? '')

            rendered = rendered.replace(/@if\s*\((.*?)\)([\s\S]*?)@else([\s\S]*?)@endif/g, (_, condition, ifTrue, elseBlock) => {
                if (condition.includes('=') && !condition.includes('==') && !condition.includes('>=') && !condition.includes('<=') && !condition.includes('!=')) {
                throw new Error('Use "==", ">=", "<=" etc. for comparison in @if. Avoid single "=" (assignment).')
                }

                const expr = condition.replace(/(\w+)/g, 'data.$1')
                return eval(expr) ? ifTrue : elseBlock
            })


            rendered = rendered.replace(/@foreach\s*\(\s*\$(\w+)\s+as\s+\$(\w+)\s*\)([\s\S]*?)@endforeach/g, (_, list, item, content) => {
                const arr = data[list]
                if (!Array.isArray(arr)) return ''
                return arr.map(val => content.replace(new RegExp(`\\{\\{\\s*${item}\\s*\\}\\}`, 'g'), val)).join('')
            })


            compiledHtml.value = `
                <html>
                <head>
                <style>${codeSections.value.style}</style>
                </head>
                <body>
                ${rendered}
                </body>
                </html>
            `
            }
        } catch (err) {
            error.value = 'Error: ' + err.message
        }
    }

</script>
