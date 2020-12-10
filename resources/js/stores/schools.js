import $axios from '../api.js'

const state = () => ({
    schools: [],
    school: {
        school_id: '',
        name: '',
        registration_number: '',
        image: '',
        school_level_id: '',
        type: '',
        acreditation: '',
        email: '',
        website: '',
        phone_number: '',
        head_master_person_id: '',
        foundation_name: '',
        location_id: '',
    },
    location: {
        location_id: '',
    },
    page: 1,
    totalpage: '',
    totalrow: '',
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.schools = payload
    },
    PUSH_DATA(state, payload) {
        state.schools.push(payload)
    },
    SPLICE_DATA(state, payload) {
        state.schools.splice(payload, 1)
    },
    ASSIGN_FORM(state, payload) {
        state.school = {
            school_id: payload.school_id,
            name: payload.name,
            registration_number: payload.registration_number,
            image: payload.image,
            school_level_id: payload.school_level_id,
            type: payload.type,
            acreditation: payload.acreditation,
            email: payload.email,
            website: payload.website,
            phone_number: payload.phone_number,
            head_master_person_id: payload.head_master_person_id,
            foundation_name: payload.foundation_name,
            location_id: payload.location_id,
        }
    },
    CLEAR_FORM(state) {
        state.school = {
            school_id: '',
            name: '',
            registration_number: '',
            image: '',
            school_level_id: '',
            type: '',
            acreditation: '',
            email: '',
            website: '',
            phone_number: '',
            head_master_person_id: '',
            foundation_name: '',
            location_id: '',
        }
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_TOTAL_PAGE(state, payload) {
        state.totalpage = payload
    }
}

const getters = {
  profileschool: state => {
      return [{
        'NPSN': state.school.registration_number,
        'Akreditasi Sekolah': state.school.acreditation,
        'Nama Yayasan': state.school.foundation_name,
        'Jenjang Pendidikan': state.school.school_level_id,
        'Kurikulum':'',
        'Waktu Kegiatan': '',
        'Kepala Sekolah': '',
        'No. Telp': state.school.phone_number,
        'Email': state.school.email,
        'Website': state.school.website,
        'Alamat Sekolah': '',
      }]
  },
  totalPageInfo: state => {
      return 'dari ' + state.totalpage + ' halaman'
  },
  totalRowInfo: state => {
      return 'dari ' + state.totalrow + ' data'
  }
}

const actions = {
    getSchools({
        commit,
        state,
        rootState,
        rootGetters
    }, payload) {
        let search = payload != undefined ? payload.search : ''
        let key = payload != undefined ? payload.key : ''
        return new Promise((resolve, reject) => {
            $axios.get(`/admin/register/schools?page=${state.page}&row=${rootState.rowpage}`, rootGetters.tokenHeader)
                .then((response) => {
                  commit('ASSIGN_DATA', response.data.data.data)
                  state.totalrow = response.data.data.total
                  state.totalpage = response.data.data.last_page
                  resolve(response.data.data)
                })
        })
    },
    detailSchool({
        commit,
        rootGetters
    }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/admin/register/schools/${payload}/show`, rootGetters.tokenHeader)
                .then((response) => {
                    if (response.data.status == 'success')
                        commit('ASSIGN_FORM', response.data.data)
                    resolve(response.data)
                })
        })
    },
    submitSchool({
        dispatch,
        state,
        rootGetters
    }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/admin/register/schools/save`, {
                    'school': [state.school],
                    'location': [state.location]
                }, rootGetters.tokenHeader)
                .then((response) => {
                    console.log(response)
                    if (response.status == 422)
                        dispatch('setErrorState', response.data, {
                            root: true
                        })
                    resolve(response.data)
                })
        })
    },
    deleteSchool({
        dispatch,
        commit,
        rootGetters
    }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/admin/register/schools/${payload}`, rootGetters.tokenHeader)
                .then((response) => {
                    resolve(response.data)
                })
        })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
