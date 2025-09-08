import { createApp } from 'vue';
import { Button } from '@/components/ui/button';
import "./app.css";

// Create a simple Vue component
const App = {
    components: {
        Button
    },
    data() {
        return {
            message: 'Hello from Vue.js with shadcn/vue!',
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
            <div class="flex gap-2">
                <Button @click="increment" variant="default">Increment</Button>
                <Button @click="count = 0" variant="outline">Reset</Button>
            </div>
        </div>
    `
};

// Mount the Vue app to the element with id="app"
const app = createApp(App);
app.mount('#app');

console.log("Vue.js is now running!");