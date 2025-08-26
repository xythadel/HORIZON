<template>
  <div class="flex h-screen w-full flex-wrap bg-zinc-800">
    <!-- Sidebar -->
    <aside class="relative flex h-screen w-60 flex-col bg-white">
      <h1 class="flex pl-10 pt-14 text-3xl font-normal text-zinc-800">Horizon</h1>
      <nav class="flex flex-col space-y-6 pl-20 pt-14">
        <a href="/dashboard" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Dashboard</a>
        <a href="/test" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Course</a>
        <a href="/mylearning" class="text-base font-normal text-zinc-800 hover:text-indigo-600">My Learning</a>
        <a href="/sandpit" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Sandpit</a>
        <a href="/badges" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Badge</a>
        <a href="/skilltest" class="text-base font-normal text-indigo-600">Skill Test</a>
        <a href="/settings" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Profiles</a>
      </nav>

      <nav class="flex flex-col space-y-6 pl-20 pt-60">
        <a href="#" @click.prevent="showLogoutModal = true" class="text-base font-normal text-zinc-800 hover:text-indigo-600">Logout</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 text-white">
      <h1 class="text-2xl font-bold mb-4">Skill Test</h1>

      <!-- If no test loaded -->
      <div v-if="!skillTest" class="text-gray-300">
        Loading skill test...
      </div>

      <!-- Show test -->
      <div v-else>
        <h2 class="text-xl font-semibold mb-2">{{ skillTest.title }}</h2>
        <p class="mb-4">{{ skillTest.description }}</p>

        <div class="mb-4">
          <label class="block mb-2 text-gray-200">Write your solution ({{ skillTest.language }})</label>
          <textarea v-model="code" rows="10" class="w-full rounded-md p-3 text-black"></textarea>
        </div>

        <button @click="runCode" class="bg-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-700">
          Run Code
        </button>

        <!-- Results -->
        <div v-if="result" class="mt-6 p-4 bg-gray-900 rounded-lg">
          <h3 class="text-lg font-semibold">Result:</h3>
          <pre>{{ result.output }}</pre>
        </div>
      </div>
    </main>

    <!-- Logout Modal -->
    <div v-if="showLogoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="w-full max-w-sm rounded-lg bg-white p-6 text-center text-gray-800 shadow-lg">
        <h2 class="mb-4 text-xl font-semibold">Are you sure?</h2>
        <p class="mb-6">Do you really want to logout from your account?</p>
        <div class="flex justify-center space-x-4">
          <button @click="confirmLogout" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600">Yes, Logout</button>
          <button @click="showLogoutModal = false" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Congratulations Modal -->
    <div v-if="showCongratsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="w-full max-w-md rounded-lg bg-white p-6 text-center text-gray-800 shadow-lg">
        <h2 class="mb-4 text-2xl font-bold text-green-600">🎉 Congratulations!</h2>
        <p class="mb-6">You passed this skill test successfully.</p>
        <div class="flex justify-center space-x-4">
          <button @click="loadNextTest" class="rounded bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Next Test</button>
          <button @click="showCongratsModal = false" class="rounded bg-gray-300 px-4 py-2 text-gray-800 hover:bg-gray-400">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import axios from "axios";

const { auth } = usePage().props;
const user = auth?.user;

const showLogoutModal = ref(false);
const showCongratsModal = ref(false);
const skillTest = ref(null);
const code = ref("");
const result = ref(null);
const currentTestId = ref(1); // keep track of test id

function confirmLogout() {
  router.post("/logout", {}, {
    onFinish: () => {
      window.location.href = "/logout";
    },
  });
}

// Load skill test
async function loadSkillTest(testId) {
  try {
    const res = await axios.get(`/api/skill-tests/2`);
    skillTest.value = res.data;
    code.value = "";
    result.value = null;
  } catch (err) {
    console.error(err);
  }
}

// Run Code
async function runCode() {
  try {
    const res = await axios.post("/api/skill-tests/run", {
      code: code.value,
      language: skillTest.value.language,
      test_id: skillTest.value.id,
    });
    result.value = res.data;

    // check if success
    if (res.data.correct) {
      showCongratsModal.value = true;
    }
  } catch (err) {
    result.value = "Error running code";
  }
}

// Load next test
async function loadNextTest() {
  showCongratsModal.value = false;
  currentTestId.value++;
  await loadSkillTest(currentTestId.value);
}

onMounted(() => {
  loadSkillTest(currentTestId.value);
});
</script>
