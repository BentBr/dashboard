<template>
    <div class="container">
        <v-hover
        v-slot:default="{ hover }"
        >
            <v-card
            class="mx-auto"
            max-width="344px"
            height="200px"
            :elevation="hover ? 16 : 2"
            >
                <v-card-title>
                    {{ clientName }}
                </v-card-title>
                <v-card-text>
                    Logins: {{ loginCount }}
                    <br>
                    Visits: {{ visitCount }}
                </v-card-text>
            </v-card>
        </v-hover>
    </div>
</template>

<script>
    export default {
        name: "CardComponent",
        props: {
            clientName: null,
            visitCount: null,
            loginCount: null,
        }
    }
</script>
