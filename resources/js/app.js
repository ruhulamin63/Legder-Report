import './bootstrap';

import { createApp } from 'vue';

// Vuetify
// import 'vuetify/styles'
// import { createVuetify } from 'vuetify'
// import * as components from 'vuetify/components'
// import * as directives from 'vuetify/directives'

import router from './route.js';

import app from './components/HomePage.vue';

// const vuetify = createVuetify({
//     components,
//     directives,
//   })
  
createApp(app)
    // .use(vuetify)
    .use(router)
    .mount('#app');
