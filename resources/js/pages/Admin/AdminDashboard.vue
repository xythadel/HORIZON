<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-4">Course Management</h1>

    <form @submit.prevent="createCourse" class="mb-6 flex gap-4">
      <input v-model="form.name" placeholder="Course Name" class="border p-2 rounded" required />
      <input v-model="form.description" placeholder="Description" class="border p-2 rounded" />
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Course</button>
    </form>

    <table class="min-w-full border">
      <thead>
        <tr class="bg-gray-200">
          <th class="p-2 border">Name</th>
          <th class="p-2 border">Description</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="course in courses" :key="course.id">
          <td class="p-2 border">
            <input v-model="course.name" class="w-full border p-1" />
          </td>
          <td class="p-2 border">
            <input v-model="course.description" class="w-full border p-1" />
          </td>
          <td class="p-2 border">
            <button @click="updateCourse(course)" class="text-blue-600 px-2">Update</button>
            <button @click="deleteCourse(course.id)" class="text-red-600 px-2">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const courses = ref([])
const form = ref({
  name: '',
  description: ''
})

const fetchCourses = async () => {
  const response = await axios.get('/api/courses')
  courses.value = response.data
}

const createCourse = async () => {
  await axios.post('/api/courses', form.value)
  form.value.name = ''
  form.value.description = ''
  fetchCourses()
}

const updateCourse = async (course) => {
  await axios.put(`/api/courses/${course.id}`, {
    name: course.name,
    description: course.description
  })
  fetchCourses()
}

const deleteCourse = async (id) => {
  await axios.delete(`/api/courses/${id}`)
  fetchCourses()
}

onMounted(fetchCourses)
</script>
