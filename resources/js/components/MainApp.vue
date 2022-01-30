<template>
    <div>
        <Header/>
        <b-container fluid="sm" class="pt-2 pb-3">
            <Calendar/>
        </b-container>
    </div>
</template>

<script>
    import Header from './layouts/Header.vue';
    import Calendar from './Calendar.vue';

    export default {
        components: {
            Header,
            Calendar
        },
        mounted() {
            
        }
    }
</script>
