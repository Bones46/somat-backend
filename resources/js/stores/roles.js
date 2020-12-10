import $axios from '../api.js'

const state = () => ({
    roles: [],
    permissions: [],
    selectPermissions: [],
    role: {
        id: '',
        name: '',
        slug: ''
    },
    page: 1,
    totalpage: '',
    totalrow: '',
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.roles = payload
    },
    PUSH_DATA(state, payload) {
        state.roles.push(payload)
    },
    SPLICE_DATA(state, payload) {
        state.roles.splice(payload, 1)
    },
    ASSIGN_FORM(state, payload) {
        state.role = {
            id: payload.id,
            name: payload.name,
            slug: payload.slug
        }
        for (let i = 0; i < payload.permissions.length; i++) {
            // console.log(permissions[i])
            state.permissions.push({
                permission_id: payload.permissions[i].id,
                // name: payload.permissions[i].name
            })
            state.selectPermissions.push(payload.permissions[i].id)
        }
    },
    SET_ITEM(state, payload) {
        state.permissions = []
        state.selectPermissions = payload
        for (const permission of state.selectPermissions) {
            if (!Number.isInteger(permission)) continue
            state.permissions.push({
              permission_id: permission
            })
        }
    },
    CLEAR_FORM(state) {
        state.role = {
            id: '',
            name: '',
            slug: ''
        }
        state.permissions = []
        state.selectPermissions = []
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_TOTAL_PAGE(state, payload) {
        state.totalpage = payload
    }
}

const getters = {
    totalPageInfo: state => {
        return 'dari ' + state.totalpage + ' halaman'
    },
    totalRowInfo: state => {
        return 'dari ' + state.totalrow + ' data'
    },
    totalPermissions: state => {
        return state.permissions.length
    }
}

const actions = {
    getRoles({
        commit,
        state,
        rootState,
        rootGetters
    }, payload) {
        let search = payload != undefined ? payload.search : ''
        let key = payload != undefined ? payload.key : ''
        return new Promise((resolve, reject) => {
            $axios.get(`/admin/role?page=${state.page}&row=${rootState.rowpage}`, rootGetters.tokenHeader)
                .then((response) => {
                    commit('ASSIGN_DATA', response.data.data)
                    // state.totalrow = response.data.data.total
                    // state.totalpage = response.data.data.last_page
                    resolve(response.data.data)
                })
        })
    },
    detailRoles({
        commit,
        rootGetters
    }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/admin/role/${payload}`, rootGetters.tokenHeader)
                .then((response) => {
                    if (response.data.status == 'success')
                        commit('ASSIGN_FORM', response.data.data)
                    resolve(response.data)
                })
        })
    },
    submitRoles({
        dispatch,
        commit,
        state,
        getters,
        rootGetters
    }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/admin/role/save`, {
                    'role': state.role,
                    'group': state.permissions
                }, rootGetters.tokenHeader)
                .then((response) => {
                    // console.log(response)
                    if (response.status == 422)
                        dispatch('setErrorState', response.data, {
                            root: true
                        })
                    resolve(response.data)
                })
        })
    },
    deleteRoles({
        dispatch,
        commit,
        rootGetters
    }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/admin/role/${payload}/delete`, rootGetters.tokenHeader)
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
