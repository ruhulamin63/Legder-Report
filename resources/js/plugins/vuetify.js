// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// $ npm install @mdi/font -D
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader

// npm install @fortawesome/fontawesome-free -D
import '@fortawesome/fontawesome-free/css/all.css' // Ensure your project is capable of handling css files
import { aliases, fa } from 'vuetify/iconsets/fa'

const vuetify = createVuetify({
  components,
  directives,

  icons: {
      iconfont: 'mdi', // default - only for display purposes
      defaultSet: 'fa',
      aliases,
      sets: {
        fa,
      },
  },
})

export default vuetify;