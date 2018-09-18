<template>
    <div class="row">
        <div v-for="server in servers" class="col-sm-6">
            <div class="card" v-bind:class="server.status">
                <h2>{{ server.name }}</h2>
                <div v-if="server.status === 'free'">
                    <p><span class="glyphicon glyphicon-list"></span></p>
                    <span class="white"><span class="glyphicon glyphicon-user white"></span></span>
                    <block-server v-bind:server="server"></block-server>
                </div>
                <div v-else-if="server.status === 'blocked'">
                    <p><span class="glyphicon glyphicon-list"></span> {{ server.description }}</p>
                    <span class="white"><span class="glyphicon glyphicon-user white"></span> {{ server.blockedBy }}</span>
                    <free-server v-bind:server="server"></free-server>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import BlockServer from "./block-server";
    import FreeServer from "./free-server";

    export default {
        name: "all-server",
        components: {FreeServer, BlockServer},
        data: function () {
            return {
                getServerRoute: '/secure/api/server/get',
                servers: [],
            }
        },
        created: function () {
            axios.get(this.getServerRoute).then((response) => {
                console.log(response.data.data);
                if (response.data.data) {
                    this.servers = response.data.data.servers;
                } else {
                    console.error('no data from server');
                }
            }, (response) => {
                console.log('ERROR: ' + response);
            });
        },
    }
</script>
