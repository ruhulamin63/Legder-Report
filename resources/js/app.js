import './bootstrap';

import { createApp } from 'vue';
import router from './plugins/route.js';

import app from './components/HomePage.vue';
import vuetify from './plugins/vuetify.js';
  
createApp(app)
    .use(vuetify)
    .use(router)
    .mount('#app');
