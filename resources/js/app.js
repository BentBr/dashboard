/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('card-component', require('./components/CardComponent.vue').default);
Vue.component('reload-component', require('./components/ReloadComponent').default);

import Vuetify, {
    VContainer,
    VContent,
    VRow,
    VApp,
    VCard,
    VCardTitle,
    VCardText,
    VSnackbar,
    VHover,
    VBtn,
    VLayout,
    VFlex
} from 'vuetify/lib'

Vue.use(Vuetify, {
    components: {
        VContainer,
        VContent,
        VRow,
        VApp,
        VCard,
        VCardTitle,
        VCardText,
        VSnackbar,
        VHover,
        VBtn,
        VLayout,
        VFlex,
    }
})

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
