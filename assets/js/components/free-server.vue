<template>
    <div>
        <br>
        <button class="btn btn-default btn-circle" data-toggle="modal" v-bind:data-target="'#freeServerModal' + server.id"
                aria-expanded="false" aria-controls="nav-collapse1">Set free <span class="glyphicon glyphicon-check"></span></button>

        <!-- Modal -->
        <div class="modal fade" v-bind:id="'freeServerModal' + server.id" tabindex="-1" role="dialog" aria-labelledby="addServerModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addServerModalLabel">Set <b>{{ server.name }}</b> free</h4>
                    </div>
                    <div class="modal-body">
                        <!--<div class="form-group">
                            <label for="serverName">Server Name</label>
                            <input v-model="description" type="text" class="form-control" id="serverName" placeholder="Pinky Moon-Moon">
                        </div>-->
                        Are you sure that you want to set the status of {{ server.name }} to 'free'?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" v-on:click="freeServer" class="btn btn-primary">Set free</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "free-server",
        props: ['server'],
        data: function () {
            return {
                blockServerRoute: '/secure/api/server/free/post',
            }
        },
        methods: {
            freeServer: function () {
                axios.post(this.blockServerRoute, {
                    id: this.server.id,
                })
                .then((response)  => {
                    if (response.status === 200) {
                        location.reload();
                        //TODO RELOAD THE ALL-SERVER COMPONENT
                    }
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
        }
    }
</script>