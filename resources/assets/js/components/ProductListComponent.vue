<template>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th>Product</th>
                <th>Price</th>
                <th>Category</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products">
                <td>
                    <img :src='"../" + product.image' width="125">
                </td>
                <td>{{ product.name }}</td>
                <td>{{ product.price }}</td>
                <td>{{ product.product_categories.display }}</td>
                <td>
                    <div class='btn-control'>
                        <button class='btn btn-success btn-xs' @click="edit(product.id)">
                            Edit
                        </button>
                        <button class='btn btn-danger btn-xs' @click="destroy(product.id)">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
    export default {
        data() {
            return { products: [] };
        },
        mounted() {
            this.getList();
            this.$parent.$on('reloadList', reload => {
                if (reload) this.getList();
            });
        },
        methods: {
            edit(id) {
                axios.get('product/' + id + '/find').then(response => {
                    this.$parent.$emit('product', response.data);
                    $('#modalEditProduct').modal();
                });
            },
            destroy(id) {
                const confirmation = window.confirm('Are you sure?');
                if (!confirmation) return;
                axios.delete('product/' + id).then(response => {
                    alert(response.data.message);
                    this.getList();
                });
            },
            getList() {
                axios.get('product/list').then(response => this.products = response.data);
            }
        }
    }
</script>