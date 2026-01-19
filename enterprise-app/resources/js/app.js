import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

// --- ของเดิม (ลบทิ้ง หรือ comment ไว้ก็ได้) ---
// import ExampleComponent from './components/ExampleComponent.vue';
// app.component('example-component', ExampleComponent);

// --- ของใหม่: ลงทะเบียน EmployeeList ---
import EmployeeList from './components/EmployeeList.vue';
app.component('employee-list', EmployeeList);

app.mount('#app');