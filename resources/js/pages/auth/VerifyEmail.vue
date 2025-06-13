<script setup>
import { ref } from 'vue'
import GuestLayout from '@/layouts/GuestLayout.vue'
import InputLabel from '@/components/InputLabel.vue'
import TextInput from '@/components/TextInput.vue'
import InputError from '@/components/InputError.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
  email: String,
})

const form = useForm({
  code: '',
  email: props.email,
})

const resendMessage = ref('')
const resendProcessing = ref(false)

const submit = () => {
  form.post('/verify-code')
}

const resend = async () => {
  resendProcessing.value = true
  resendMessage.value = ''
  await form.post('/resend-code', {
    preserveScroll: true,
    onSuccess: () => {
      resendMessage.value = 'A new code has been sent to your email.'
    },
    onFinish: () => {
      resendProcessing.value = false
    },
  })
}
</script>

<template>
  <GuestLayout>
    <Head title="Verify Your Email" />

    <div class="mb-6 text-center">
      <h1 class="text-2xl font-bold mb-2 text-white">Verify Your Email</h1>
      <p class="text-sm text-gray-300">
        We sent a 6-digit verification code to your email:
        <span class="font-semibold">{{ email }}</span>
      </p>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <div>
        <InputLabel for="code" value="Verification Code" class="text-white" />
        <TextInput
          id="code"
          type="text"
          v-model="form.code"
          maxlength="6"
          required
          class="mt-1 block w-full"
          autocomplete="one-time-code"
        />
        <InputError class="mt-2" :message="form.errors.code" />
      </div>

      <div class="flex items-center justify-between">
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Verify
        </PrimaryButton>

        <button
          type="button"
          class="text-sm text-gray-300 underline hover:text-white"
          :disabled="resendProcessing"
          @click="resend"
        >
          Resend Code
        </button>
      </div>

      <p v-if="resendMessage" class="text-green-400 text-sm mt-2">
        {{ resendMessage }}
      </p>
    </form>
  </GuestLayout>
</template>
