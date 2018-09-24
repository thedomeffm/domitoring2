<template>
    <div class="float-error">
        {{ message }}
    </div>
</template>

<script>
    export default {
        name: "connection-error",
        props: ['message'],
        methods: {
            reload: function () {
                location.reload();
            }
        }
    }
</script>

<style scoped>
    .float-error {
        padding: 5px 15px;
        background-color: red;
        border: #fff solid 1px;
        border-radius: 5px;
        box-shadow: 0 6px 10px 0 #666;
        transition: all 0.1s ease-in-out;

        color: white;
        font-weight: bold;
        text-align: center;
        line-height: 70px;

        position: fixed;
        right: auto;
        max-width: 800px;
        width: 50vw;
        bottom: 50%;
    }
</style>