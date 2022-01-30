<template>
    <div>
        <b-card title="Calendar">
            <b-row>
                <b-col sm="4" class="mb-3">
                    <b-form @submit="onSubmit">
                        <b-row>
                            <!-- Event Title -->
                            <b-col>
                                <b-form-group label="Event" label-for="calendar_event">
                                    <b-form-input
                                        id="calendar_event"
                                        v-model="form.event"
                                        placeholder="Enter Event Name"
                                        required
                                        :disabled="loading"
                                    ></b-form-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row class="mb-3">
                            <!-- From and To Date -->
                            <b-col>
                                <label>From</label>
                                <datepicker v-model="form.from_date" format="yyyy-MM-dd" :disabled="loading" :required="true"></datepicker>
                            </b-col>
                            <b-col>
                                <label>To</label>
                                <datepicker v-model="form.to_date" format="yyyy-MM-dd" :disabled="loading" :required="true"></datepicker>
                            </b-col>
                        </b-row>
                        <b-row>
                            <!-- Checkbox Days -->
                            <b-col>
                                <b-form-group v-slot="{ ariaDescribedby }">
                                    <b-form-checkbox-group
                                        v-model="form.days"
                                        :options="days"
                                        :aria-describedby="ariaDescribedby"
                                        name="calendar-days"
                                        :disabled="loading"
                                    >
                                    </b-form-checkbox-group>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <!-- Submit button -->
                            <b-col>
                                <b-button variant="primary" type="submit" :disabled="loading">Submit</b-button>
                            </b-col>
                        </b-row>
                    </b-form>
                </b-col>
                <b-col sm="8">
                    <template v-if="errors.length > 0">
                        <b-alert show variant="danger"><a href="#" class="alert-link" v-for="(error, index) in errors" v-bind:key="index">{{ error[0] }}</a></b-alert>
                    </template>

                    <b-skeleton-wrapper :loading="loadingDates">
                        <template #loading>
                            <b-skeleton width="25%" height="40px"></b-skeleton>
                            <br/>
                            <b-skeleton width="55%" height="20px"></b-skeleton>
                            <b-skeleton width="70%" height="20px"></b-skeleton>
                            <b-skeleton width="55%" height="20px"></b-skeleton>
                            <b-skeleton width="65%" height="20px"></b-skeleton>
                        </template>

                        <h1>{{ monthYear }}</h1>
                        <!-- Table -->
                        <table class="table">
                            <thead>
                            </thead>
                            <tbody>
                                <template>
                                    <tr v-for="(date, index) in dates" v-bind:key="index" :class="date.event ? 'has-event' : ''">
                                        <td style="width: 25%">{{ date.text }}</td>
                                        <td v-if="date.event">{{ date.event }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </b-skeleton-wrapper>
                </b-col>
            </b-row>
        </b-card>
    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import _ from 'lodash';

    export default {
        name: "Calendar",
        components: {
            Datepicker,
        },
        mounted () {
            this.$data.form.from_date = new Date(this.current.getFullYear(), this.current.getMonth(), 1);
            this.$data.form.to_date = new Date(this.current.getFullYear(), this.current.getMonth() + 1, 0);
        },
        data() {
            return {
                form: {
                    event: '',
                    from_date: '',
                    to_date: '',
                    days: []
                }
            }
        },
        methods: {
            onSubmit(e) {
                e.preventDefault();
                
                let data = _.cloneDeep(this.$data.form);
                data.days = JSON.stringify(data.days);
                data.from_date = moment(data.from_date).format("YYYY-MM-DD");
                data.to_date = moment(data.to_date).format("YYYY-MM-DD");
                
                this.$store.dispatch("calendar/storeCalendar", data)
                    .then(() => {
                        this.$bvToast.toast('Event Successfully saved', {
                            title: 'Success',
                            variant: 'success',
                            solid: true
                        });
                    })
                    .catch(() => {
                        this.$bvToast.toast('Something went wrong', {
                            title: 'Error',
                            variant: 'danger',
                            solid: true
                        });
                    });
            }
        },
        computed: {
            loading() {
                return this.$store.getters["calendar/loading"]; 
            },
            loadingDates(){
                return this.$store.getters["calendar/loadingDates"];
            },
            errors() {
                return this.$store.getters["calendar/errors"];
            },
            dates() {
                return this.$store.getters["calendar/dates"];
            },
            days() {
                return this.$store.getters["calendar/days"];
            },
            current() {
                return this.$store.getters["calendar/current"];
            },
            monthYear(){
                return this.$store.getters["calendar/monthYear"];
            }
        },
        watch: {
            'form.from_date': function(newValue) {
                if(this.$data.form.to_date != '' && this.$data.form.to_date < newValue){
                    this.$bvToast.toast('Invalid Dates', {
                        title: 'Error',
                        variant: 'danger',
                        solid: true
                    });
                    this.$store.commit("calendar/setBetweenDaysFromDates", []);
                    return;
                }
            },
            'form.to_date': function(newValue, oldValue) {
                if(newValue < this.$data.form.from_date){
                    this.$bvToast.toast('Invalid Dates', {
                        title: 'Error',
                        variant: 'danger',
                        solid: true
                    });
                    this.$store.commit("calendar/setBetweenDaysFromDates", []);
                    return;
                }

                this.$store.commit("calendar/setMonthYear", moment(newValue).format('MMM YYYY'));
                this.$store.dispatch("calendar/getBetweenDates", { from_date: moment(this.$data.form.from_date).format('YYYY-MM-DD'), to_date: moment(newValue).format('YYYY-MM-DD') });
            }
        },
    }
</script>

<style>
    .vdp-datepicker > div > input{
        border-radius: 3px;
        padding: 0.45em 0.5em;
        font-size: 100%;
        border: 1px solid #ccc;
        width: 100%;
    }

    .has-event{
        background-color: #dbf7fa85;
    }
</style>

<style scoped>
</style>