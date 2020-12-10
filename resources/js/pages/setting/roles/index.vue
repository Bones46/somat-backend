<template>
<v-layout wrap>
    <v-flex xs12>
        <v-breadcrumbs :items="breadcrumbs" class="pt-0 xs12">
            <template v-slot:divider>
                <v-icon>forward</v-icon>
            </template>
        </v-breadcrumbs>
        <v-flex xs12 class="elevation-10 white">
            <v-toolbar flat color="white">
                <v-toolbar-title>Daftar Data Roles</v-toolbar-title>
                <v-divider class="mx-2" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <template v-slot:extension>
                    <v-dialog v-model="dialog" max-width="900px" persistent scrollable>
                        <template v-slot:activator="{ on }">
                            <v-btn dense color="success" class="caption" dark v-on="on">
                                <v-icon>library_add</v-icon>
                                <div class="hidden-xs-only ml-2">Tambah Role</div>
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text class="pt-0">
                                <v-card>
                                    <v-card-title class="green darken-4 white--text">
                                        <span class=".body-2">Role</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container fluid pa-0>
                                            <v-layout wrap>
                                                <v-flex xs12 sm4 class="px-1">
                                                    <v-flex xs12>
                                                        <v-text-field v-model="role.name" :error="Array.isArray(errors.name)" :error-messages="errors.name" label="Nama Role *"></v-text-field>
                                                    </v-flex xs12>
                                                </v-flex>
                                                <v-flex xs12 sm8 class="px-1">
                                                    <v-flex xs12>
                                                        <v-text-field v-model="role.slug" :error="Array.isArray(errors.slug)" :error-messages="errors.slug" label="Slug *"></v-text-field>
                                                    </v-flex>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </v-card-text>

                            <v-card-text class="pt-0">
                                <v-card>
                                    <v-card-title class="green darken-4 white--text">
                                        <span class=".body-2">Permissions</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container fluid pa-0>
                                            <v-layout wrap>
                                                <v-flex xs12 sm12 class="px-1">
                                                    <v-flex xs12>
                                                        <v-treeview :open="AllPermissions.opentree" transition v-model="selectPermissions" :items="AllPermissions.tree" active-class="grey lighten-4 indigo--text" selected-color="indigo" open-on-click selectable>
                                                        </v-treeview>
                                                    </v-flex>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="close">Batal</v-btn>
                                <v-btn color="success" @click="save">Simpan</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-menu bottom origin="center center" transition="scale-transition">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" class="caption" dark v-on="on" dense>
                                <v-icon>touch_app</v-icon>
                                <div class="hidden-xs-only ml-2">Pilih Opsi</div>
                            </v-btn>
                        </template>

                        <v-list subheader dense>
                            <v-list-tile avatar @click="">
                                <v-list-tile-avatar>
                                    <v-icon>trending_flat</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-title class="mr-4">Opsi 1</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-spacer></v-spacer>
                    <v-btn dense color="primary" class="caption">
                        <v-icon>search</v-icon>
                        <div class="hidden-xs-only ml-2">Pencarian</div>
                    </v-btn>
                </template>
            </v-toolbar>
            <v-flex xs12 px-4>
                <b-table responsive hover bordered :items="roles" :fields="fields">
                    <template slot="actions" slot-scope="row">
                        <v-icon small @click="editRole(row.item)">
                            edit
                        </v-icon>
                        <v-icon small @click="deleteRole(row.item)">
                            delete
                        </v-icon>
                    </template>
                </b-table>
                <v-container pt-0 pb-4>
                    <v-layout row class="white">
                        <v-flex xs12 md5 lg4>
                            <b-input-group size="md" prepend="Tampilkan" :append="totalRowInfo" class="py-2 pagelimit">
                                <b-form-select v-model="rowpage" :options="pageoptions"></b-form-select>
                            </b-input-group>
                        </v-flex>
                        <v-flex xs12 md6 lg5 offset-md1 offset-lg3>
                            <b-input-group size="md" class="py-2 paginate">
                                <b-input-group-prepend>
                                    <span class="input-group-text">Halaman</span>
                                    <b-button variant="outline-secondary" @click="prevPage">
                                        <v-icon size="18">skip_previous</v-icon>
                                    </b-button>
                                </b-input-group-prepend>
                                <b-form-input v-model="page" type="number" min="1"></b-form-input>
                                <b-input-group-append>
                                    <b-button variant="outline-secondary" @click="nextPage">
                                        <v-icon size="18">skip_next</v-icon>
                                    </b-button>
                                    <span class="input-group-text">{{totalPageInfo}}</span>
                                </b-input-group-append>
                            </b-input-group>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-flex>
    </v-flex>
</v-layout>
</template>

<script>
import {
    mapActions,
    mapMutations,
    mapGetters,
    mapState
} from 'vuex'

export default {
    data() {
        return {
            breadcrumbs: [{
                    text: 'Pengaturan',
                    disabled: true,
                },
                {
                    text: 'Roles',
                    disabled: false,
                    href: '#'
                }
            ],
            test:[82,83],
            dialog: false,
            editedIndex: -1,
            totalRows: 1,
            fields: [{
                    label: 'Nama Role',
                    class: 'text-xs-left',
                    key: 'name'
                },
                {
                    label: 'Slug',
                    class: 'text-xs-left',
                    key: 'slug'
                },
                {
                    label: 'Aksi',
                    class: 'text-xs-center',
                    key: 'actions'
                }
            ],
            allPermissions: [{
                    permission_id: 1,
                    name: 'Create Role'
                },
                {
                    permission_id: 2,
                    name: 'Delete Role'
                },
                {
                    permission_id: 3,
                    name: 'Detail Role'
                },
                {
                    permission_id: 4,
                    name: 'Create Template'
                },
            ],
        }
    },

    computed: {
        ...mapState(['errors', 'rowpage', 'pageoptions']),
        ...mapState('roles', {
            roles: state => state.roles,
            role: state => state.role
        }),
        ...mapGetters('roles', ['totalPageInfo', 'totalRowInfo', 'totalPermissions']),
        ...mapGetters('routegroups', ['AllPermissions']),
        formTitle() {
            return this.editedIndex === -1 ? 'Tambah Data Roles' : 'Ubah Data Roles'
        },
        //treeview
        // permissions() {
        //     const permissions = []
        //
        //     for (const permission of this.selectPermissions) {
        //         if (!Number.isInteger(permission)) continue
        //
        //         permissions.push(permission)
        //     }
        //
        //     return permissions
        // },
        selectPermissions: {
            get() {
                return this.$store.state.roles.selectPermissions
            },
            set(val) {
                this.$store.commit('roles/SET_ITEM', val)
            }
        },
        //end
        rowpage: {
            get() {
                return this.$store.state.rowpage
            },
            set(val) {
                this.$store.commit('SET_ROWPAGE', val)
            }
        },
        page: {
            get() {
                return this.$store.state.roles.page
            },
            set(val) {
                this.$store.commit('roles/SET_PAGE', val)
            }
        }
    },

    watch: {
        rowpage() {
            this.$store.commit('roles/SET_PAGE', 1)
            this.getRoles()
        },
        page() {
            this.getRoles()
        }
    },

    created() {
        this.getRoles()
        this.getGroups()
        if (!this.perPage) this.perPage = this.rowpage
    },

    methods: {
        ...mapActions('roles', ['submitRoles', 'getRoles', 'deleteRoles']),
        ...mapActions('routegroups', ['getGroups']),
        save() {
            this.submitRoles().then((response) => {
                if (response.status == 'success') {
                    if (this.editedIndex > -1) {
                        Object.assign(this.roles[this.editedIndex], response.data)
                    } else {
                        this.$store.commit('roles/PUSH_DATA', response.data)
                        this.$store.state.roles.totalrow++
                    }
                    this.close()
                }
            })
        },
        editRole(item) {
            this.$store.commit('roles/ASSIGN_FORM', item)
            //console.log(item)
            this.editedIndex = this.roles.indexOf(item)
            this.dialog = true
        },
        deleteRole(item) {
            this.$swal({
                title: 'Kamu Yakin?',
                text: "Tindakan ini akan menghapus secara permanent!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!'
            }).then((result) => {
                if (result.value) {
                    this.deleteRoles(item.id).then((response) => {
                        if (response.status == 'success') {
                            this.$store.state.roles.totalrow--
                            const index = this.roles.indexOf(item)
                            this.$store.commit('roles/SPLICE_DATA', index)
                        }
                    })
                }
            })
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.$store.commit('roles/CLEAR_FORM')
                this.$store.commit('CLEAR_ERRORS')
                this.editedIndex = -1
            }, 300)
        },
        prevPage() {
            if (this.page > 1) {
                this.page--
            }
        },
        nextPage() {
            if (this.page < this.$store.state.roles.totalpage) {
                this.page++
            }
        },
        toggle() {
            this.$nextTick(() => {
                if (this.selectAll) {
                    this.permissions = []
                } else {
                    this.permissions = this.allPermissions.slice()
                }
            })
        }
    }
}
</script>
