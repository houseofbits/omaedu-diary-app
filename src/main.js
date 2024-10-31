import { createApp } from 'vue'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import './style.css'
import App from './App.vue'


// const myCustomLightTheme = {
//     dark: false,
//     colors: {
//       background: '#FFFFFF',
//       surface: '#FFFFFF',
//       primary: '#6200EE',
//       'primary-darken-1': '#3700B3',
//       secondary: '#03DAC6',
//       'secondary-darken-1': '#018786',
//       error: '#B00020',
//       info: '#2196F3',
//       success: '#4CAF50',
//       warning: '#FB8C00',
//     },
//   }

const vuetify = createVuetify({
    components,
    directives,
    // theme: {
    //     defaultTheme: 'myCustomLightTheme',
    //     themes: {
    //       myCustomLightTheme,
    //     },
    //   },    
})

createApp(App)
    .provide('userCredentials', '8yP5aDBBnj1kS9Xxtn6akjhmUkhUTjVWWDczZUtYWkVNTDlSWVE9PQ')
    .use(vuetify)
    .mount('#vue-app');

// const query = new URLSearchParams(window.location.search);

// if (query.has('identity')) {
//     createApp(App)
//         .provide('userCredentials', query.get('identity'))
//         .use(vuetify)
//         .mount('#vue-app');
// }

