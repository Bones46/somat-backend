<template>
<div>
    <v-toolbar color="green darken-4" dark fixed app clipped-left>
        <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
        <v-toolbar-title class="hidden-xs-only">School<strong>Connect</strong></v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon>
            <v-icon dark>home</v-icon>
        </v-btn>
        <v-badge overlap color="red darken-4">
            <template v-slot:badge>
                <span>3</span>
            </template>
            <v-btn icon>
                <v-icon dark>notifications</v-icon>
            </v-btn>
        </v-badge>
        <v-btn icon class="mr-3">
            <v-icon dark>email</v-icon>
        </v-btn>
        <v-toolbar-items>
            <v-menu v-model="menu" :close-on-content-click="false" :nudge-width="100" offset-y>
                <template v-slot:activator="{ on }">
                    <v-btn flat v-on="on" class="width0">
                        <strong class="mr-2 hidden-xs-only">Selamat Datang, {{profile.username}}</strong>
                        <v-avatar size="40">
                            <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
                        </v-avatar>
                    </v-btn>
                </template>
                <v-card>
                    <v-list>
                        <v-list-tile avatar>
                            <v-list-tile-avatar size="60" class="ma-2">
                                <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
                            </v-list-tile-avatar>

                            <v-list-tile-content>
                                <v-list-tile-title>John Leider</v-list-tile-title>
                                <v-list-tile-sub-title>sinnbo@gmail.com</v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>

                    <v-divider></v-divider>

                    <v-list subheader dense>
                        <v-list-tile avatar @click="">
                            <v-list-tile-avatar>
                                <v-icon>person</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-title>Profile Saya</v-list-tile-title>
                        </v-list-tile>

                        <v-list-tile avatar @click="">
                            <v-list-tile-avatar>
                                <v-icon>security</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-title>Ubah Password</v-list-tile-title>
                        </v-list-tile>

                        <v-list-tile avatar @click="">
                            <v-list-tile-avatar>
                                <v-icon>settings</v-icon>
                            </v-list-tile-avatar>
                            <v-list-tile-title>Pengaturan</v-list-tile-title>
                        </v-list-tile>
                    </v-list>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn small color="error" @click.prevent="postLogout">Logout</v-btn>
                    </v-card-actions>
                </v-card>
            </v-menu>
        </v-toolbar-items>
    </v-toolbar>
    <v-navigation-drawer v-model="drawer" fixed clipped app>
        <v-toolbar class="hidden-sm-and-up" color="green darken-4" dark>
            <v-toolbar-title>School<strong>Connect</strong></v-toolbar-title>
        </v-toolbar>
        <v-img :aspect-ratio="16/8" src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg">
            <v-layout pa-2 column fill-height class="lightbox white--text">
                <v-spacer></v-spacer>
                <v-flex shrink>
                    <div class="subheading"><strong>SMAN 38 JAKARTA</strong></div>
                    <div class="body-1">Jl. Raya Lenteng Agung,</br>Jakarta Selatan</div>
                </v-flex>
            </v-layout>
        </v-img>
        <v-subheader class="pa-4 black--text grey lighten-2">Main Navigation</v-subheader>
        <v-list dense>
            <v-flex v-for="(mlist, i) in menulist" :key="i">
                <v-list-tile v-if="mlist.countChild == 0" :to="mlist.uri">
                    <v-list-tile-action>
                        <v-icon v-text="mlist.icon" size="24"></v-icon>
                    </v-list-tile-action>
                    <v-list-tile-title v-text="mlist.title"></v-list-tile-title>
                </v-list-tile>
                <v-list-group v-else :prepend-icon="mlist.icon">
                    <template v-slot:activator>
                        <v-list-tile>
                            <v-list-tile-title v-text="mlist.title"></v-list-tile-title>
                        </v-list-tile>
                    </template>
                    <v-flex v-for="(nlist, j) in mlist.childmenu" :key="j" class="green accent-1">
                        <v-list-tile v-if="nlist.countChild == 0" :to="nlist.uri">
                            <v-list-tile-action></v-list-tile-action>
                            <v-list-tile-title v-text="nlist.title"></v-list-tile-title>
                        </v-list-tile>
                        <v-list-group v-else no-action sub-group>
                            <template v-slot:activator>
                                <v-list-tile>
                                    <v-list-tile-title v-text="nlist.title"></v-list-tile-title>
                                </v-list-tile>
                            </template>
                            <v-list-tile v-for="(olist, k) in nlist.childmenu" :key="k" :to="olist.uri" class="green lighten-5">
                                <v-list-tile-title v-text="olist.title"></v-list-tile-title>
                            </v-list-tile>
                        </v-list-group>
                    </v-flex>
                </v-list-group>
            </v-flex>
        </v-list>
    </v-navigation-drawer>
</div>
</template>
<script>
import {
    mapActions,
    mapMutations,
    mapGetters,
    mapState,
} from 'vuex';
export default {
    data: () => ({
        drawer: null,
        menu: false,
        admins: [
            ['Management', 'people_outline'],
            ['Settings', 'settings']
        ],
        cruds: [
            ['Create', 'add'],
            ['Read', 'insert_drive_file'],
            ['Update', 'update'],
            ['Delete', 'delete']
        ],
        menulist: [{
                title: 'Dashboard',
                icon: 'home',
                uri: '/admin/dashboard',
                countChild: 0,
            },
            {
                title: 'Registrasi Data',
                icon: 'insert_drive_file',
                uri: '',
                countChild: 4,
                childmenu: [{
                        title: 'Data Siswa',
                        icon: 'people_outline',
                        uri: '/admin/register/students',
                        countChild: 0,
                    },
                    {
                        title: 'Data Pegawai',
                        icon: 'people_outline',
                        uri: '/admin/register/employees',
                        countChild: 0,
                    },
                    {
                        title: 'Profile Sekolah',
                        icon: 'developer_board',
                        uri: '/admin/profile/school',
                        countChild: 0,
                    },
                    {
                        title: 'Data Rekanan',
                        icon: 'people_outline',
                        uri: '/admin/register/partners',
                        countChild: 0,
                    },
                    {
                        title: 'Data Sekolah',
                        icon: '',
                        uri: '/admin/register/schools',
                        countChild: 0,
                    },
                ],
            },
            {
                title: 'Penjadwalan',
                icon: 'schedule',
                uri: '/',
                countChild: 4,
                childmenu: [{
                        title: 'Jadwal Pelajaran',
                        icon: 'schedule',
                        uri: '/admin/schedule/lessons',
                        countChild: 0,
                    },
                    {
                        title: 'Jadwal Ekstrakurikuler',
                        icon: 'schedule',
                        uri: '/admin/schedule/extracurricular',
                        countChild: 0,
                    },
                ],
            },
            {
                title: 'Pengaturan',
                icon: 'settings',
                uri: '',
                countChild: 1,
                childmenu: [{
                        title: 'Route Groups',
                        icon: 'blur_on',
                        uri: '/admin/setting/routegroups',
                        countChild: 0,
                    },
                    {
                        title: 'Roles',
                        icon: 'blur_on',
                        uri: '/admin/setting/roles',
                        countChild: 0,
                    },
                ],
            },
            {
                title: 'Test',
                icon: 'blur_on',
                uri: '/',
                countChild: 1,
                childmenu: [{
                    title: 'Test 1.1',
                    icon: 'blur_on',
                    uri: '/',
                    countChild: 1,
                    childmenu: [{
                        'title': 'Test 1.1.1',
                        'icon': 'blur_on',
                        'uri': '/',
                        countChild: 0
                    }]
                }]
            },
        ]
    }),
    props: {
        source: String
    },
    created() {
        this.getUserProfile()
    },
    computed: {
        ...mapGetters(['isAuth']),
        ...mapState(['profile']),
    },
    methods: {
        ...mapActions('auth', ['doLogout', 'checkResponseStatus', 'getUserProfile']),
        postLogout() {
            this.doLogout(this.data).then((response) => {
                this.checkResponseStatus(response)
                console.log(this.isAuth);
                if (!this.isAuth) {
                    this.$router.push({
                        name: 'login'
                    })
                }
            })
        }
    }
}
</script>
