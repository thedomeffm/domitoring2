import Vue from 'vue';

/*
import transFilter from 'vue-trans';
Vue.use(transFilter);
*/

// SCSS (+Bootstrap)
require('../css/app.scss');

// global jQuery
var $ = require('jquery');

// bootstrap JS
require('bootstrap-sass');

// my components
import allServer from './components/all-server';
import addServer from './components/add-server';
import blockServer from './components/block-server';
import freeServer from './components/free-server';

// vue
var view = new Vue({
    el: '#app',
    components: {
        allServer,
        addServer,
        blockServer,
        freeServer,
    }
});
