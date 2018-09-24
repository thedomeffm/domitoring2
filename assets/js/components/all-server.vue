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
                    <!--<span class="white"><span class="glyphicon glyphicon-user white"></span> {{ server.blockedBy }} (since: {{ server.blockedSince | moment("from", "now", true) }})</span>-->
                    <span class="white"><span class="glyphicon glyphicon-user white"></span> {{ server.blockedBy }} (since: {{ moment(server.blockedSince).fromNow(true) }})</span>
                    <free-server v-bind:server="server"></free-server>
                </div>
            </div>
        </div>
        <div v-if="err">
            <connection-error v-bind:message="errMsg"></connection-error>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import BlockServer from "./block-server";
    import FreeServer from "./free-server";
    import ConnectionError from "./connection-error";

    export default {
        name: "all-server",
        components: {ConnectionError, FreeServer, BlockServer},
        data: function () {
            return {
                getServerRoute: '/secure/api/server/get',
                servers: [],
                err: false,
                errMsg: '',
            }
        },
        methods: {
            getServers: function () {
                axios.get(this.getServerRoute).then((response) => {
                    console.log(response.data.data);
                    if (response.data.data) {
                        this.servers = response.data.data.servers;
                        this.err = false;
                    } else {
                        console.error('data is empty');
                        this.err = true;
                        this.errMsg = 'data body is empty';
                    }
                }, (response) => {
                    console.log('ERROR: ' + response);
                    this.err = true;
                    this.errMsg = 'Cant reach server!';
                });
            },
        },
        created: function () {
            this.getServers();
            setInterval(function () {
                this.getServers();
            }.bind(this), 10000)
        },
    }
</script>
