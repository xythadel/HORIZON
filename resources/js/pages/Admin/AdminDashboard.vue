<template>
  <div>
    <h1>Course Management</h1>

    <div v-for="course in courses" :key="course.id" class="course-card">
      <h2>{{ course.name }}</h2>
      <p>{{ course.description }}</p>

      <!-- Topic form -->
      <div>
        <input v-model="newTopic[course.id]" placeholder="Add a topic" />
        <button @click="addTopic(course.id)">Add Topic</button>
      </div>

      <!-- Topics List -->
      <ul>
        <li v-for="topic in course.topics" :key="topic.id">
          <input v-model="topic.name" />
          <button @click="updateTopic(topic)">Update</button>
          <button @click="deleteTopic(topic)">Delete</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['courses'], // Each course must have a `topics` array
  data() {
    return {
      newTopic: {}, // Holds new topic name per course
    };
  },
  methods: {
    addTopic(courseId) {
      axios.post('/topics', {
        name: this.newTopic[courseId],
        course_id: courseId,
      }).then(() => {
        location.reload(); // For simplicity, refresh after add
      });
    },
    updateTopic(topic) {
      axios.put(`/topics/${topic.id}`, {
        name: topic.name,
      });
    },
    deleteTopic(topic) {
      axios.delete(`/topics/${topic.id}`).then(() => {
        location.reload();
      });
    },
  },
};
</script>

<style>
.course-card {
  border: 1px solid #ddd;
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 8px;
}
</style>
