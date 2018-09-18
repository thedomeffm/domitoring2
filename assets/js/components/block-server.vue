<template>
    <div>
        <br>
        <button class="btn btn-default btn-circle" data-toggle="modal" v-bind:data-target="'#blockServerModal' + server.id"
                aria-expanded="false" aria-controls="nav-collapse1">Block this Server <span class="glyphicon glyphicon-edit"></span></button>

        <!-- Modal -->
        <div class="modal fade" v-bind:id="'blockServerModal' + server.id" tabindex="-1" role="dialog" aria-labelledby="addServerModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addServerModalLabel">Block <b>{{ server.name }}</b></h4>
                    </div>
                    <div class="modal-body">
                        <div v-if="errMsg" class="alert alert-danger" role="alert">
                            {{ errMsg }}
                        </div>
                        <div class="form-group">
                            <label for="description">Why do you block this server?</label>
                            <input v-model="description" type="text" class="form-control" id="description" placeholder="Ticket No.">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" v-on:click="blockServer" class="btn btn-primary">Block</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "block-server",
        props: ['server'],
        data: function () {
            return {
                blockServerRoute: '/secure/api/server/block/post',
                description: '',
                errMsg: '',
            }
        },
        methods: {
            blockServer: function () {
                //console.log(this.server.id);
                //console.log(this.description);
                if (!this.description) {
                    this.errMsg = 'Please set a reason!'
                } else {
                    axios({
                        method: 'post',
                        url: this.blockServerRoute,
                        data: {
                            id: this.server.id,
                            description: this.description,
                        }
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
                }


            },
        }
    }
</script>

<style scoped>

</style>