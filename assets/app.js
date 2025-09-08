import { createApp } from 'vue';
import App from './App.vue';
import "./app.css";

// Mount the Vue app to the element with id="app"
const app = createApp(App);
app.mount('#app');

console.log("Vue.js is now running with .vue files!");