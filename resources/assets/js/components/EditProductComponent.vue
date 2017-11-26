<template>
<div class="modal fade" id='modalEditProduct' tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update Product</h4>
      </div>
      <div class="modal-body">
        <form enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" v-model="productModel.name" name="name" id="name" placeholder="Goku God T-Shirt">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" v-model="productModel.description" name="description" id="description" placeholder="type your text"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image of your product</label>
                <input type="file" name="image" id="image" @change="upload">
                <p class="help-block">Ka me ha me ha ...</p>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="text" name="price" v-model="productModel.price" class="form-control" id="price" placeholder="price">
            </div>
            <div class="form-group">
                <label for="product_categories_id">Product Category</label>
                <select class="form-control" name="product_categories_id" id="product_categories_id" v-model="productModel.product_categories_id" @input="handleSelect($event)">
                    <option><- Product category -></option>
                    <option v-for="category of categories" :value="category.id">
                        {{ category.display }} 
                    </option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" @click="submit" class="btn btn-success" id='buttonSubmit'>Submit</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
// const productModel = { name: '', description: '', image: '', price: '', product_categories_id: '', csrf_token: Laravel.csrfToken };
 export default {
        props: ['product'],
        mounted() {
            this.$parent.$on('product', product => {
                this.productModel = product;
            });
            axios.get('product/list-categories').then(response => this.categories = response.data);
        },
        data() {
            return { productModel: {}, categories: [] };
        },
        methods: {
        upload: function(event) {
            event.preventDefault();
            const files = event.target.files;
            const data = new FormData();
            data.append('image', files[0]);
            axios.post('product/upload-image', data).then((response) => {
                if (response.status !== 200) return;
                this.$data.productModel.image = response.data.file;

            }).catch(function (response, status, request) {
                console.log(response, status, request);
            });
        },
        submit: function(event) {
            axios.patch('product/' + this.$data.productModel.id, this.$data.productModel).then((response) => {
                alert(response.data.message);
                this.$parent.$emit('reloadList', true);
            }).catch(error => {
                this.catchErrors(error);
            });
        },
        catchErrors: function(errorObject) {
            switch (errorObject.request.status) {
                case 422:
                    let errors = '';
                    for (let prop in errorObject.response.data.errors) {
                        if (prop !== undefined && errorObject.response.data.errors[prop] !== undefined) {
                            errors += prop + ': ' + errorObject.response.data.errors[prop] + '\n';
                        }
                    }
                    alert(errors);
                break;
                default:
                    alert(response.data.message);
            }
        },
        handleSelect(teste) {
            this.$data.productModel.product_categories_id = teste.target.value;
        }
    }
 }

</script>
