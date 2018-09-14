<template>
    <div>
        <mail-button></mail-button>

        <!-- Modal -->
        <div class="modal fade" id="newMail" tabindex="-1" role="dialog" aria-labelledby="newMailLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="newMailLabel">{{ 'new_mailing_meassure'|trans }}</h4>
                    </div>

                    <form method="post" v-bind:action="newMailPath">

                        <div class="modal-body">

                            <div>
                                <input name="eventId" id="eventId" type="hidden" v-bind:value="eventId">
                            </div>

                            <div>
                                <label class="control-label required" for="mail_type">{{ 'typ' | trans }}</label>
                                <select v-model="type" v-on:click="getMailings()" id="mail_type" class="form-control">
                                    <option v-for="type in types" v-bind:value="type.value">{{ type.name }}</option>
                                </select>
                            </div>

                            <div v-if="showMailing">
                                <label class="control-label required m-t-15" for="mailing">{{ 'mailing' | trans }}</label>
                                <select v-model="mailing" v-on:click="getCalendarWeeks()" id="mailing" name="mailing" class="form-control">
                                    <option v-for="mail in mailings" v-bind:value="mail.id">{{ mail.name }}</option>
                                </select>
                            </div>

                            <div v-if="showCalendarWeek">
                                <label class="control-label required m-t-15" for="calendarWeek">{{ 'calendarWeek' | trans }}</label>
                                <select v-model="calendarWeek" id="calendarWeek" v-on:click="getReasons" name="calendarWeek" class="form-control">
                                    <option :class="{ ownMeasure: cw.ownMeasure, full : cw.full, optional : cw.optional }" :disabled="cw.full || cw.ownMeasure" v-for="cw in calendarWeeks" v-bind:value="cw.id">
                                        {{ cw.sendYear }} | {{ cw.sendWeek }}
                                        <span v-if="cw.ownMeasure">({{'already_registered' | trans }})</span>
                                        <span v-else-if="cw.full" >({{'full' | trans}})</span>
                                        <span v-else-if="cw.optional">({{'optional' | trans}})</span>
                                    </option>
                                </select>
                            </div>

                            <div v-if="showReasons">
                                <label class="control-label required m-t-15" for="reasons">{{ 'reason' | trans }}</label>
                                <select v-model="reason" id="reasons" name="reason" class="form-control">
                                    <option v-for="reason in reasons" v-bind:value="reason">{{reason}}</option>
                                </select>
                            </div>


                            <div v-if="showReasons">
                                <label class="control-label m-t-15" for="info">{{ 'more_Information' | trans }}</label>
                                <textarea maxlength="1000" id="info" name="otherInformation" class="form-control" placeholder="Optional für weitere Informationen"></textarea>
                            </div>

                            <div v-if="showNoItemsFound" class="m-t-15 p15">
                                <div class="alert alert-warning">{{ 'no_elements_found' | trans }}</div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ 'abort' | trans }}</button>
                            <button class="btn btn-primary" type="submit" :disabled="isDisabled">{{ 'submit' | trans }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import MailButton from './mailButton';

export default {
    props: ['eventId'],
    name: 'newMail',
    components: {MailButton},
    data: function () {
        return {
            newMailPath: '/secure/measure/add/mailing',
            type: '',
            types: [
                {
                    'name': 'TicketTicker',
                    'value' : 'ticket_ticker'
                },
                {
                    'name': 'Regional',
                    'value' : 'regional'
                },
                {
                    'name': 'Landesweit',
                    'value' : 'nationwide'
                }
            ],
            mailing: '',
            mailings: [],
            showMailing: false,
            calendarWeek: '',
			calendarWeeks: [],
            showCalendarWeek: false,
            reason: '',
            reasons: [],
            showReasons: false,
            showNoItemsFound: false
        }
    },

    methods: {
        getMailings: function() {
            //reset
            this.mailing = '';
            this.mailings = [];
            this.calendarWeek = '';
            this.calendarWeeks = [];
            this.showNoItemsFound = false;
            this.showCalendarWeek = false;
            this.showReasons = false;
            axios.get('/secure/api/mail/' + this.type).then((response) => {
                if (response.data.data) {
                    this.mailings = response.data.data.mailings;
                    this.showMailing = true;
                } else {
                    console.error('Expected "data" got: ' + response.data);
                }
            }, (response) => {
                console.error('ERROR: '+response);
            }).finally(() => {
                if (this.mailings.length === 0) {
                    this.showNoItemsFound = true;
                    this.showMailing = false;
                }
            });
        },

        getCalendarWeeks: function () {
            //reset
            this.calendarWeek = '';
            this.calendarWeeks = [];
            this.showNoItemsFound = false;
            this.showCalendarWeek = false;
            axios.get('/secure/api/calendarWeeks/' + this.mailing + '?eventID=' + this.eventId).then((response) => {
                console.log(response.data.data.calendarWeeks);
                if (response.data.data) {
                    this.calendarWeeks = response.data.data.calendarWeeks;
                    this.showCalendarWeek = true;
                } else {
                    console.error('Expected "data" got: ' + response.data);
                }
            }, (response) => {
                console.log('ERROR: '+response);
            }). finally(() => {
                if (this.calendarWeeks.length === 0) {
                    this.showCalendarWeek = false;
                    this.showNoItemsFound = true;
                }
            });
        },

        isOptional: function(calendarWeek) {
            if (calendarWeek.optional === 'true') {
                return false;
            }
            return true;
        },

        getReasons: function () {
            //reset
            this.reason = '';
            this.reasons = [];
            this.showNoItemsFound = false;
            this.showReasons = false;
            axios.get('/secure/api/reasons').then((response) => {
                if (response.data.data) {
                    this.reasons = response.data.data.reasons;
                    this.showReasons = true;
                } else {
                    console.error('Expected "data" got: ' + response.data);
                }
            }, (response) => {
                console.error('ERROR: ' + response);
            }). finally(() => {
                if (this.reasons.length === 0) {
                    this.showReason = false;
                    this.showNoItemsFound = true;
                }
            });
        }
    },

    computed: {
        isFull: function() {

        },

        isDisabled: function() {
            if (this.calendarWeek !== '' && this.mailing !== '' && this.reason !== '') {
                return false;
            }
            return true;
        }
    }
}
</script>