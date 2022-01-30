import _ from 'lodash';

export const calendar = {
    namespaced: true,
    state: {
        loading: false,
        loadingDates: false,
        errors: [],
        dates: [
            {
                date: "1 Sun"
            },
            {
                date: "2 Sun"
            }
        ],
        days: [
            {
                text: 'Mon', value: 'monday'
            },
            {
                text: 'Tue', value: 'tuesday'
            },
            {
                text: 'Wed', value: 'wednesday'
            },
            {
                text: 'Thu', value: 'thursday'
            },
            {
                text: 'Fri', value: 'friday'
            },
            {
                text: 'Sat', value: 'saturday'
            },
            {
                text: 'Sun', value: 'sunday'
            },
        ],
        current: new Date(),
        monthYear: ''
    },
    getters: {
        loading(state){
            return state.loading
        },
        loadingDates(state){
            return state.loadingDates;
        },
        errors(state){
            return state.errors
        },
        dates(state){
            return state.dates;
        },
        days(state){
            return state.days;
        },
        current(state){
            return state.current;
        },
        monthYear(state){
            return state.monthYear;
        }
    },
    mutations: {
        requestStoreCalendar(state){
            state.loading = true;
            state.errors = [];
        },
        successStoreCalendar(state, payload){
            state.dates = payload;
            state.loading = false;
            state.errors = [];
        },
        failedStoreCalendar(state, payload){
            state.loading = false;
            state.errors = payload;
        },
        requestBetweenFromDates(state){
            state.loadingDates = true;
        },
        setBetweenDaysFromDates(state, payload){
            state.dates = payload;
            state.loadingDates = false;
        },
        failedBetweenDaysFromDates(state, payload){
            state.errors = payload;
        },
        setMonthYear(state, payload){
            state.monthYear = payload;
        }
    },
    actions: {
        storeCalendar(context, payload){
            return new Promise((ok, rej) => {
                context.commit("requestStoreCalendar");

                axios.post("api/calendar", payload)
                    .then((res) => {
                        context.commit("successStoreCalendar", res.data);
                        ok();
                    })
                    .catch((err) => {
                        context.commit("failedStoreCalendar", err.response.data.errors);
                        rej();
                    });
            });
        },
        getBetweenDates(context, payload){
            return new Promise((ok, rej) => {
                context.commit("requestBetweenFromDates");

                axios.get(`api/calendar/in-between-dates?from_date=${payload.from_date}&to_date=${payload.to_date}`)
                    .then((res) => {
                        context.commit("setBetweenDaysFromDates", res.data);
                        ok();
                    })
                    .catch((err) => {
                        context.commit("failedBetweenDaysFromDates", err.response.data.errors);
                        rej();
                    });
            });
        }
    }
}