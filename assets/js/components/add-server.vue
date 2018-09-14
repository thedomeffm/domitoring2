<template>
    <div>
        <button class="btn btn-default btn-outline btn-circle" data-toggle="modal" data-target="#addServerModal"
           aria-expanded="false" aria-controls="nav-collapse1"><b>+</b> Server</button>

        <!-- Modal -->
        <div class="modal fade" id="addServerModal" tabindex="-1" role="dialog" aria-labelledby="addServerModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addServerModalLabel">Add a new Server</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="serverName">Server Name</label>
                            <input v-model="serverName" type="text" class="form-control" id="serverName" placeholder="Pinky Moon-Moon">
                            <div v-if="msgError">
                                <br>
                                <div class="alert alert-danger" role="alert">
                                    {{ this.msgError }}
                                </div>
                            </div>

                        </div>
                        <div class="form-group">
                            <label for="serverImage">Image upload (250kb max)</label>
                            <input disabled type="file" id="serverImage">
                            <p class="help-block">will be here in v2.1</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" :disabled="isDisabled" v-on:click="postServer" class="btn btn-primary">Gogo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "add-server",
        data: function () {
            return {
                postServerRoute: '/secure/api/server/post',
                serverName: '',
                msgError: null,
            }
        },
        methods: {
            postServer: function () {
                axios.post(this.postServerRoute, {
                    name: this.serverName,
                })
                .then((response) => {
                    if (response.status === 222) {
                        this.msgError = 'Name already exist!';
                    }

                    if (response.status === 200) {
                        location.reload();
                        //TODO RELOAD THE ALL-SERVER COMPONENT
                    }
                    console.log(response);
                });
            }
        },
        computed: {
            isDisabled: function() {
                if (this.serverName !== '') {
                    return false;
                }
                return true;
            }
        }
    }
</script>
