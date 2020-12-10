<template>
<v-layout wrap>
    <v-flex xs12>
        <v-breadcrumbs :items="breadcrumbs" class="pt-0 xs12">
            <template v-slot:divider>
                <v-icon>forward</v-icon>
            </template>
        </v-breadcrumbs>
    </v-flex>
    <v-flex xs12 class="elevation-10 white">
        <v-toolbar flat color="white">
            <v-toolbar-title>Profile Sekolah</v-toolbar-title>
            <v-divider class="mx-2" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="900px" persistent scrollable>
                <template v-slot:activator="{ on }">
                    <v-btn dense color="success" class="caption" dark v-on="on">
                        <v-icon>edit</v-icon>
                        <div class="hidden-xs-only ml-2">Ubah</div>
                    </v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="headline">Ubah Data Sekolah</span>
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
                                                <v-select v-model="school.school_level_id" :error="Array.isArray(errors.school_level_id)" :error-messages="errors.school_level_id" :items="selectItems.schoolLevel" item-value="value" item-text="display"
                                                  label="Jenjang Pendidikan *"></v-select>
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
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="close">Batal</v-btn>
                        <v-btn color="success" @click="save">Simpan</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-flex xs12 px-4 pb-4 pt-2>
            <v-container fluid pa-0>
                <v-layout wrap class="elevation-1">
                    <v-flex align-self-center text-xs-center xs12 sm4>
                      <v-avatar size="180">
                          <img src="https://cdn.vuetifyjs.com/images/john.jpg" alt="John">
                      </v-avatar>
                    </v-flex>
                    <v-flex xs12 sm8>
                      <v-toolbar flat>
                        <v-spacer></v-spacer>
                        <v-toolbar-title>{{school.name}}</v-toolbar-title>
                        <v-spacer></v-spacer>
                      </v-toolbar>
                        <b-table stacked borderless outlined responsive :items="profileschool" class="pb-4 mb-0"></b-table>
                    </v-flex>
                </v-layout>
            </v-container>
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
            dialog: false,
            breadcrumbs: [{
                    text: 'Registrasi Data',
                    disabled: true,
                },
                {
                    text: 'Profile Sekolah',
                    disabled: false,
                    href: '#'
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
        }
    },
    computed: {
        ...mapState(['errors', 'rowpage', 'pageoptions']),
        ...mapGetters('schools',['profileschool']),
        ...mapState('schools', {
            school: state => state.school,
            location: state => state.location
        })
    },
    created() {
        this.detailSchool(6) // 6 => school_id
    },
    methods: {
        ...mapActions('auth', ['checkResponse']),
        ...mapActions('schools', ['submitSchool', 'updateSchool','detailSchool','profileSchool']),
        save() {
            if (this.editedIndex > -1) {
                this.updateSchool().then((response) => {
                    if (response.status == 'success') {
                        Object.assign(this.schools[this.editedIndex], this.school)
                        this.close()
                    }
                })
            } else {
                this.submitSchool().then((response) => {
                    if (response.status == 'success') {
                        this.$store.commit('schools/PUSH_DATA', response.data)
                        this.$store.state.schools.totalrow++
                        this.close()
                    }
                })
            }
        },
        editItem(item) {
            this.$store.commit('schools/ASSIGN_FORM', item)
            this.editedIndex = this.schools.indexOf(item)
            this.dialog = true
        },
        close() {
            this.dialog = false
            setTimeout(() => {
                this.$store.commit('CLEAR_ERRORS')
                this.editedIndex = -1
            }, 300)
        }

    }
}
</script>
