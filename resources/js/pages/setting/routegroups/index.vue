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
                <v-toolbar-title>Daftar Data Route Groups</v-toolbar-title>
                <v-divider class="mx-2" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <template v-slot:extension>
                    <v-dialog v-model="dialog" max-width="900px" persistent scrollable>
                        <template v-slot:activator="{ on }">
                            <v-btn dense color="success" class="caption" dark v-on="on">
                                <v-icon>library_add</v-icon>
                                <div class="hidden-xs-only ml-2">Tambah Route Group</div>
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text class="pt-0">
                                <v-card>
                                    <v-card-title class="green darken-4 white--text">
                                        <span class=".body-2">Group</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container fluid pa-0>
                                            <v-layout wrap>
                                                <v-flex xs12 sm4 class="px-1">
                                                    <v-flex xs12>
                                                        <v-text-field v-model="group.name" :error="Array.isArray(errors.name)" :error-messages="errors.name" label="Nama Group *"></v-text-field>
                                                    </v-flex xs12>
                                                </v-flex>
                                                <v-flex xs12 sm8 class="px-1">
                                                    <v-flex xs12>
                                                        <v-text-field v-model="group.description" :error="Array.isArray(errors.description)" :error-messages="errors.description" label="Deskripsi *"></v-text-field>
                                                    </v-flex>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                                <v-card class="mt-3">
                                    <v-card-title class="green darken-4 white--text py-1">
                                        <span class=".body-2">Item Route</span>
                                        <v-spacer></v-spacer>
                                        <v-btn dense color="success" class="caption" dark @click="addItem">
                                            <v-icon>library_add</v-icon>
                                            <div class="hidden-xs-only ml-2">Tambah Item</div>
                                        </v-btn>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container fluid pa-0>
                                            <v-layout wrap>
                                                <v-flex xs12 class="px-1">
                                                    <v-flex xs12>
                                                        <b-table responsive hover bordered :items="routeItems" :fields="itemFields" class="tableform">
                                                            <template slot="name" slot-scope="row">
                                                                <div v-if="!row.item.isUpdate" class="py-2">{{row.item.name}}</div>
                                                                <v-text-field v-if="row.item.isUpdate" v-model="row.item.name" label="Nama *"></v-text-field>
                                                            </template>
                                                            <template slot="slug" slot-scope="row">
                                                                <div v-if="!row.item.isUpdate" class="py-2">{{row.item.slug}}</div>
                                                                <v-text-field v-if="row.item.isUpdate" v-model="row.item.slug" label="Slug *"></v-text-field>
                                                            </template>
                                                            <template slot="http_path" slot-scope="row">
                                                                <div v-if="!row.item.isUpdate" class="py-2">{{row.item.http_path}}</div>
                                                                <v-text-field v-if="row.item.isUpdate" v-model="row.item.http_path" label="URL Path *"></v-text-field>
                                                            </template>
                                                            <template slot="menu_flag" slot-scope="row">
                                                                <div v-if="!row.item.isUpdate" class="py-2">{{row.item.menu_flag}}</div>
                                                                <v-checkbox v-if="row.item.isUpdate" v-model="row.item.menu_flag" color="primary" :label="row.item.menu_flag" true-value="Y" false-value="N"></v-checkbox>
                                                            </template>
                                                            <template slot="actions" slot-scope="row">
                                                                <v-icon v-if="!row.item.isUpdate" small @click="editItem(row.item)" class="py-2">
                                                                    edit
                                                                </v-icon>
                                                                <v-icon small @click="deleteItem(row.item)" class="py-2">
                                                                    delete
                                                                </v-icon>
                                                            </template>
                                                        </b-table>
                                                    </v-flex xs12>
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
                <b-table responsive hover bordered :items="groups" :fields="fields">
                    <template slot="actions" slot-scope="row">
                        <v-icon small @click="editGroup(row.item)">
                            edit
                        </v-icon>
                        <v-icon small @click="deleteGroup(row.item)">
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
                    text: 'Route Groups',
                    disabled: false,
                    href: '#'
                }
            ],
            dialog: false,
            editedIndex: -1,
            totalRows: 1,
            fields: [{
                    label: 'Nama Route Group',
                    class: 'text-xs-left',
                    key: 'name'
                },
                {
                    label: 'Deskripsi',
                    class: 'text-xs-left',
                    key: 'description'
                },
                {
                    label: 'Aksi',
                    class: 'text-xs-center',
                    key: 'actions'
                }
            ],
            itemFields: [{
                    label: 'Nama',
                    class: 'text-xs-center',
                    key: 'name'
                },
                {
                    label: 'Slug',
                    class: 'text-xs-center',
                    key: 'slug'
                },{
                        label: 'URL Path',
                        class: 'text-xs-center',
                        key: 'http_path'
                    },
                    {
                        label: 'Menu Flag',
                        class: 'text-xs-center',
                        key: 'menu_flag'
                    },
                {
                    label: 'Aksi',
                    class: 'text-xs-center',
                    key: 'actions'
                }
            ],
        }
    },
    computed: {
        ...mapState(['errors', 'rowpage', 'pageoptions']),
        ...mapState('routegroups', {
            groups: state => state.groups,
            group: state => state.group,
            routeItems: state => state.routeItems
        }),
        ...mapGetters('routegroups', ['totalPageInfo', 'totalRowInfo']),
        formTitle() {
            return this.editedIndex === -1 ? 'Tambah Data Route Groups' : 'Ubah Data Route Groups'
        },
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
                return this.$store.state.schools.page
            },
            set(val) {
                this.$store.commit('schools/SET_PAGE', val)
            }
        }
    },

    watch: {
        rowpage() {
            this.$store.commit('routegroups/SET_PAGE', 1)
            this.getGroups()
        },
        page() {
            this.getGroups()
        }
    },

    created() {
        this.getGroups()
        if (!this.perPage) this.perPage = this.rowpage
    },

    methods: {
        ...mapActions('routegroups', ['submitGroups', 'getGroups', 'deleteGroups']),
        save() {
            this.submitGroups().then((response) => {
                if (response.status == 'success') {
                    if (this.editedIndex > -1) {
                        Object.assign(this.groups[this.editedIndex], response.data)
                    } else {
                        this.$store.commit('routegroups/PUSH_DATA', response.data)
                        this.$store.state.routegroups.totalrow++
                    }
                    this.close()
                }
            })
        },
        editGroup(item) {
            this.$store.commit('routegroups/ASSIGN_FORM', item)
            //console.log(item)
            this.editedIndex = this.groups.indexOf(item)
            this.dialog = true
        },
        deleteGroup(item) {
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
                    this.deleteGroups(item.template_id).then((response) => {
                        if (response.status == 'success') {
                            this.$store.state.groups.totalrow--
                            const index = this.groups.indexOf(item)
                            this.$store.commit('routegroups/SPLICE_DATA', index)
                        }
                    })
                }
            })
        },
        addItem() {
            this.$store.commit('routegroups/ADD_ITEM_FORM')
        },
        editItem(item) {
            item.isUpdate = true
        },
        deleteItem(item) {
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
                  const index = this.routeItems.indexOf(item)
                  this.$store.commit('routegroups/SPLICE_ITEM_FORM', index)
                }
            })
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.$store.commit('routegroups/CLEAR_FORM')
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
            if (this.page < this.$store.state.schools.totalpage) {
                this.page++
            }
        }
    }
}
</script>
