<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Courses</h1>

    <button
      class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
      @click="showModal = true"
    >
      Add Course
    </button>

    <table class="min-w-full mt-6 border border-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-2 px-4 border-b text-left">ID</th>
          <th class="py-2 px-4 border-b text-left">Name</th>
          <th class="py-2 px-4 border-b text-left">Description</th>
          <th class="py-2 px-4 border-b text-left">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="course in courses" :key="course.id">
          <td class="py-2 px-4 border-b">{{ course.id }}</td>
          <td class="py-2 px-4 border-b">{{ course.name }}</td>
          <td class="py-2 px-4 border-b">{{ course.description }}</td>
          <td class="py-2 px-4 border-b space-x-2">
            <button
              class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
              @click="editCourse(course)"
            >
              Edit
            </button>
            <button
              class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
              @click="deleteCourse(course.id)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded shadow w-1/2">
        <h2 class="text-lg font-semibold mb-4">
          {{ editingCourse ? 'Edit Course' : 'Add Course' }}
        </h2>
        <form @submit.prevent="saveCourse">
          <input
            type="text"
            v-model="form.name"
            placeholder="Course Name"
            class="w-full border rounded p-2 mb-4"
          />
          <textarea
            v-model="form.description"
            placeholder="Course Description"
            class="w-full border rounded p-2 mb-4"
          ></textarea>
          <div class="flex justify-end space-x-2">
            <button
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            >
              Save
            </button>
            <button
              type="button"
              class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
              @click="closeModal"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      showModal: false,
      courses: [],
      form: {
        name: '',
        description: ''
      },
      editingCourse: null
    };
  },
  mounted() {
    this.loadCourses();
  },
  methods: {
    loadCourses() {
      axios.get('/api/courses')
        .then(response => {
          this.courses = response.data;
        })
        .catch(error => console.error(error));
    },
    saveCourse() {
      if (this.editingCourse) {
        axios.put(`/api/courses/${this.editingCourse.id}`, this.form)
          .then(() => {
            this.loadCourses();
            this.closeModal();
          })
          .catch(error => console.error(error));
      } else {
        axios.post('/api/courses', this.form)
          .then(() => {
            this.loadCourses();
            this.closeModal();
          })
          .catch(error => console.error(error));
      }
    },
    deleteCourse(id) {
      axios.delete(`/api/courses/${id}`)
        .then(() => this.loadCourses())
        .catch(error => console.error(error));
    },
    editCourse(course) {
      this.editingCourse = course;
      this.form = { ...course };
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.editingCourse = null;
      this.form = { name: '', description: '' };
    }
  }
};
</script>

<style scoped>
/* No @apply used, Tailwind classes applied inline */
</style>
