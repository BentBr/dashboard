<template>
    <div class="container">
        <v-btn>
            <span>reloading every 10 secs</span>
        </v-btn>
    </div>
</template>

<script>
    export default {
        name: "ReloadComponent",

        data () {
            return {
                active: true
            }
        },

        methods: {
            //making sure page is reloading frequently
            autoUpdate: function () {
                if (this.active === true) {
                    setInterval((function () {
                        //quick and dirty reload
                        location.reload()

                    }),10000);
                }
            }
        },

        //invoking of reloading function after component rendered
        created() {
            this.autoUpdate();
        },
    }
</script>
