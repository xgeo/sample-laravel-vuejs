
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

window.Vue = require('vue');

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest'
  };

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('create-product-component', require('./components/CreateProductComponent.vue'));
Vue.component('product-list', require('./components/ProductListComponent.vue'));
Vue.component('edit-product', require('./components/EditProductComponent.vue'));
Vue.component('import-csv', require('./components/ImportCsvComponent.vue'));

const productCategorieModel = { id: '', display: '' };

const app = new Vue({
    el: '#app',
    data() {
        return {
            product: { id: '', name: '', description: '', 
                        image: '', price: '', 
                        product_categories_id: '' }
        };
    }
});
