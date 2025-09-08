import { createApp } from 'vue';
import "./app.css";

// Create a simple Vue component
const App = {
    data() {
        return {
            message: 'Hello from Vue.js!',
            count: 0
        }
    },
    methods: {
        increment() {
            this.count++;
        }
    },
    template: `
        <div class="vue-app">
            <h2 class="text-blue-500 text-2xl font-bold underline">{{ message }}</h2>
            <p>Count: {{ count }}</p>
            <button @click="increment" class="bg-blue-500 text-white px-4 py-2 rounded">Increment</button>
        </div>
    `
};

// Mount the Vue app to the element with id="app"
const app = createApp(App);
app.mount('#app');

console.log("Vue.js is now running!");