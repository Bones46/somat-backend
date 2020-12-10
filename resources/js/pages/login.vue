<style scoped>
.rounded-card {
    border-radius: 10px !important;
    padding: 0px 20px 20px 20px;
}

.v-toolbar {
    margin-bottom: 5px;
    border-radius: 10px !important;
}
</style>
<template>
<v-app id="inspire">
    <v-content>
        <v-container fluid fill-height>
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md3>
                    <v-toolbar dark class="elevation-12 green darken-4">
                        <v-spacer></v-spacer>
                        <div class="display-1 font-weight-medium">SchoolConnect</div>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-form>
                        <v-card class="elevation-12 rounded-card">
                            <v-card-text class="pb-0">
                                <v-text-field small primary prepend-icon="person" name="username" label="Username" type="text" v-model="data.username"></v-text-field>
                                <v-text-field small id="password" prepend-icon="lock" name="password" label="Password" type="password" v-model="data.password"></v-text-field>
                                <v-checkbox small v-model="data.remember" label="Ingatkan Password" class="mt-0"></v-checkbox>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn type="submit" small color="primary" @click.prevent="postLogin">Login</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn flat small class="caption">Lupa Password?</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-form>
                </v-flex>
            </v-layout>
        </v-container>
    </v-content>
</v-app>
</template>

<script>
import {
    mapActions,
    mapMutations,
    mapGetters,
    mapState
} from 'vuex'

export default {
    data: () => ({
        data: {
            username: '',
            password: '',
            remember: false
        }
    }),
    props: {
        source: String
    },
    created() {

        if (this.isAuth) {
            this.$router.push({
                name: 'dashboard'
            })
        }
    },
    computed: {
        ...mapGetters(['isAuth']),
        ...mapState(['errors']),
    },
    methods: {
        ...mapActions('auth', ['doLogin', 'checkResponseStatus']),
        ...mapMutations(['CLEAR_ERRORS']),

        postLogin() {
            this.doLogin(this.data).then((response) => {
                this.checkResponseStatus(response)
                if (this.isAuth) {
                    this.CLEAR_ERRORS()
                    this.$router.push({
                        name: 'dashboard'
                    })
                }
            })
        }
    }
}
</script>
