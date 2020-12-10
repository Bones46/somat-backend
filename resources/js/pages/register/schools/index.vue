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
                <v-toolbar-title>Daftar Data Sekolah</v-toolbar-title>
                <v-divider class="mx-2" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <template v-slot:extension>
                    <v-dialog v-model="dialog" max-width="900px" persistent scrollable>
                        <template v-slot:activator="{ on }">
                            <v-btn dense color="success" class="caption" dark v-on="on">
                                <v-icon>library_add</v-icon>
                                <div class="hidden-xs-only ml-2">Tambah Sekolah</div>
                            </v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text class="pt-0">
                                <v-card>
                                    <v-card-title class="green darken-4 white--text">
                                        <span class=".body-2">Profile Sekolah</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container fluid pa-0>
                                            <v-layout wrap>
                                                <v-flex xs12 sm6 class="px-1">
                                                    <v-flex xs12>
                                                        <v-text-field v-model="school.registration_number" :error="Array.isArray(errors.registration_number)" :error-messages="errors.registration_number" label="NPSN *"></v-text-field>
                                                        <v-text-field v-model="school.name" :error="Array.isArray(errors.name)" :error-messages="errors.name" label="Nama Sekolah *"></v-text-field>
                                                        <v-select v-model="school.school_level_id" :error="Array.isArray(errors.school_level_id)" :error-messages="errors.school_level_id" :items="selectItems.schoolLevel" item-value="value"
                                                          item-text="display" label="Jenjang Pendidikan *"></v-select>
                                                        <v-select v-model="school.type" :error="Array.isArray(errors.type)" :error-messages="errors.type" :items="selectItems.schoolStatus" item-value="value" item-text="display" label="Status Sekolah">
                                                        </v-select>
                                                        <v-select v-model="school.acreditation" :error="Array.isArray(errors.acreditation)" :error-messages="errors.acreditation" :items="selectItems.schoolAcreditations" label="Akreditasi Sekolah">
                                                        </v-select>
                                                        <!-- <v-text-field v-model="editedItem.name" label="Kurikulum"></v-text-field> -->
                                                    </v-flex xs12>
                                                </v-flex>
                                                <v-flex xs12 sm6 class="px-1">
                                                    <v-flex xs12>
                                                        <v-text-field v-model="school.foundation_name" :error="Array.isArray(errors.foundation_name)" :error-messages="errors.foundation_name" label="Nama Yayasan"></v-text-field>
                                                        <!-- <v-select v-model="editedItem.name" :items="selectItems.schoolTimes" item-value="value" item-text="display" label="Waktu Kegiatan *"></v-select> -->
                                                        <v-text-field v-model="school.phone_number" :error="Array.isArray(errors.phone_number)" :error-messages="errors.phone_number" label="No Telp"></v-text-field>
                                                        <v-text-field v-model="school.email" :error="Array.isArray(errors.email)" :error-messages="errors.email" label="Email"></v-text-field>
                                                        <v-text-field v-model="school.website" :error="Array.isArray(errors.website)" :error-messages="errors.website" label="Website"></v-text-field>
                                                        <v-text-field v-model="school.head_master_person_id" label="Kepala Sekolah"></v-text-field>
                                                    </v-flex>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                                <v-card class="mt-3">
                                    <v-card-title class="green darken-4 white--text">
                                        <span class=".body-2">Alamat Lengkap Sekolah</span>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container fluid pa-0>
                                            <v-layout wrap>
                                                <v-flex xs12 sm6 class="px-1">
                                                    <v-flex xs12>
                                                        <v-textarea label="Alamat Jalan" height="100"></v-textarea>
                                                        <v-layout wrap>
                                                            <v-flex xs6>
                                                                <v-text-field class="xs6" v-model="location" label="RT"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-text-field class="xs6" v-model="location" label="RW"></v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                        <v-select :items="selectItems.locationDesa" item-value="value" item-text="display" label="Desa/Kelurahan"></v-select>
                                                    </v-flex xs12>
                                                </v-flex>
                                                <v-flex xs12 sm6 class="px-1">
                                                    <v-flex xs12>
                                                        <v-select :items="selectItems.locationCamat" item-value="value" item-text="display" label="Kecamatan"></v-select>
                                                        <v-select :items="selectItems.locationKota" item-value="value" item-text="display" label="Kabupaten/Kota"></v-select>
                                                        <v-select :items="selectItems.locationProvinsi" item-value="value" item-text="display" label="Provinsi"></v-select>
                                                        <v-text-field v-model="location" label="Kode Pos"></v-text-field>
                                                    </v-flex>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                                <v-card class="mt-3">
                                    <v-card-title class="green darken-4 white--text">
                                        <span class=".body-2">Logo Sekolah</span>
                                    </v-card-title>
                                    <v-card-text>
                                        <b-form-file v-model="imageUpload" placeholder="Choose a file..." drop-placeholder="Drop file here..." accept="image/*"></b-form-file>
                                        <cropper classname="cropper" ref="cropper" :src="imageFile" :stencil-props="ratioImage"></cropper>
                                        <button @click="crop">
                                            Crop
                                        </button>
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

                            <v-list-tile avatar @click="">
                                <v-list-tile-avatar>
                                    <v-icon>trending_flat</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-title class="mr-4">Opsi 2</v-list-tile-title>
                            </v-list-tile>

                            <v-list-tile avatar @click="">
                                <v-list-tile-avatar>
                                    <v-icon>trending_flat</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-title class="mr-4">Opsi 3</v-list-tile-title>
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
                <b-table responsive hover bordered :items="schools" :fields="fields">
                    <template slot="actions" slot-scope="row">
                        <v-icon small @click="editItem(row.item)">
                            edit
                        </v-icon>
                        <!-- <v-icon small @click="deleteItem(row.item)">
                        delete
                    </v-icon> -->
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
                    text: 'Registrasi Data',
                    disabled: true,
                },
                {
                    text: 'Data Sekolah',
                    disabled: false,
                    href: '#'
                }
            ],
            totalRows: 1,
            fields: [{
                    label: 'Nama Sekolah',
                    class: 'text-xs-left',
                    key: 'name'
                },
                {
                    label: 'Status',
                    class: 'text-xs-center',
                    key: 'type'
                },
                {
                    label: 'NPSN',
                    class: 'text-xs-center',
                    key: 'registration_number'
                },
                {
                    label: 'Akreditasi',
                    class: 'text-xs-center',
                    key: 'acreditation'
                },
                {
                    label: 'No. Telp',
                    class: 'text-xs-center',
                    key: 'phone_number'
                },
                {
                    label: 'Aksi',
                    class: 'text-xs-center',
                    key: 'actions'
                }
            ],
            selectItems: {
                schoolLevel: [{
                        value: 1,
                        display: 'Sekolah Dasar'
                    },
                    {
                        value: 2,
                        display: 'Sekolah Menengah Pertama'
                    },
                    {
                        value: 3,
                        display: 'Sekolah Menengah Atas'
                    },
                    {
                        value: 4,
                        display: 'Sekolah Menengah Kejuruan'
                    }
                ],
                schoolStatus: [{
                        value: 'NG',
                        display: 'Negeri'
                    },
                    {
                        value: 'SW',
                        display: 'Swasta'
                    },
                ],
                schoolAcreditations: ['A', 'B', 'C'],
                schoolTimes: [{
                        value: 'PG',
                        display: 'Pagi'
                    },
                    {
                        value: 'PT',
                        display: 'Siang'
                    },
                ],
                locationDesa: [{
                    value: 1,
                    display: 'Lenteng Agung'
                }, {
                    value: 2,
                    display: 'Jagakarsa'
                }],
                locationCamat: [{
                    value: 1,
                    display: 'Jagakarsa'
                }, {
                    value: 2,
                    display: 'Pasar Minggu'
                }],
                locationKota: [{
                    value: 1,
                    display: 'Jakarta Selatan'
                }, {
                    value: 2,
                    display: 'Jakarta Timur'
                }],
                locationProvinsi: [{
                    value: 1,
                    display: 'DKI Jakarta'
                }, {
                    value: 2,
                    display: 'Jawa Barat'
                }]
            },
            dialog: false,
            editedIndex: -1,
            ratioImage: {
                aspectRatio: 10 / 12
            },
            imageFile: null,
            imageUpload: null,
            coordinates: {
                width: 0,
                height: 0,
                left: 0,
                top: 0
            },
        }
    },
    computed: {
        ...mapState(['errors', 'rowpage', 'pageoptions']),
        ...mapState('schools', {
            schools: state => state.schools,
            school: state => state.school,
            location: state => state.location
        }),
        ...mapGetters('schools', ['totalPageInfo', 'totalRowInfo']),
        formTitle() {
            return this.editedIndex === -1 ? 'Tambah Data Sekolah' : 'Ubah Data Sekolah'
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
            this.$store.commit('schools/SET_PAGE', 1)
            this.getSchools()
        },
        page() {
            this.getSchools()
        },
        imageUpload() {
            this.uploadImage()
        }
    },

    created() {
        this.getSchools()
        if (!this.perPage) this.perPage = this.rowpage
    },

    methods: {
        ...mapActions('schools', ['submitSchool', 'getSchools', 'deleteSchool']),
        save() {
            this.submitSchool().then((response) => {
                if (response.status == 'success') {
                    if (this.editedIndex > -1) {
                        Object.assign(this.schools[this.editedIndex], this.school)
                    } else {
                        this.$store.commit('schools/PUSH_DATA', response.data)
                        this.$store.state.schools.totalrow++
                    }
                    this.close()
                }
            })
        },
        editItem(item) {
            this.$store.commit('schools/ASSIGN_FORM', item)
            this.editedIndex = this.schools.indexOf(item)
            this.dialog = true
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
                    this.deleteSchool(item.school_id).then((response) => {
                        if (response.status == 'success') {
                            this.$store.state.schools.totalrow--
                            const index = this.schools.indexOf(item)
                            this.$store.commit('schools/SPLICE_DATA', index)
                        }
                    })
                }
            })
        },
        uploadImage() {
            var reader = new FileReader();
            reader.readAsDataURL(this.imageUpload);
            reader.onload = (e) => {
                this.imageFile = e.target.result;
            }
        },
        crop() {
            const {
                coordinates,
                canvas
            } = this.$refs.cropper.getResult()
            this.coordinates = coordinates
            // You able to do different manipulations at a canvas
            // but there we just get a cropped image
            this.imageFile = canvas.toDataURL()
            console.log(this.$refs.cropper.getResult())
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.$store.commit('schools/CLEAR_FORM')
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
