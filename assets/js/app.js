import Vue from 'vue';
import moment from 'moment';
Vue.prototype.moment = moment;

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
import addServerButton from './components/add-server-button';
import addServerModal from './components/add-server-modal';
import blockServer from './components/block-server';
import freeServer from './components/free-server';
import connectionError from './components/connection-error';

// vue
var view = new Vue({
    el: '#app',
    components: {
        allServer,
        addServerButton,
        addServerModal,
        blockServer,
        freeServer,
        connectionError,
    }
});
