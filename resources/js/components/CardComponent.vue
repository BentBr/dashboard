<template>
    <div class="container">
        <v-card
        class="mx-auto"
        max-width="344px"
        height="200px"
        >
            <v-card-title>
                $clientName
            </v-card-title>
            <v-card-text>
                Logins: $loginCount
                <br>
                Visits: $visitCount
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
