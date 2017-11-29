<template>
    <form>
        <input type="file" name="file" @change="getFile">
        <hr>
        <button class="btn btn-success" type="button" @click="submit">Enviar</button>
    </form>
</template>
<script>
    export default {
        data() {
            return { fileUpload : '' };
        },
        methods: {
            getFile: function(event) {
                event.preventDefault();
                const files = event.target.files;
                this.$data.fileUpload = files[0];
            },
            submit: function(event) {
                const data = new FormData();
                data.append('file', this.$data.fileUpload);
                axios.post('upload-csv', data).then((response) => {
                    if (response.status !== 200) return;

                    alert(response.data.message);

                }).catch((response) => {
                    this.catchErrors(response);
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
        }
    }
</script>