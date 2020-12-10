import $axios from '../api.js'

const state = () => ({

})

const mutations = {

}

const actions = {
    doLogin({
        commit,
        state,
        getters,
        rootGetters
    }, payload) {
        localStorage.setItem('token', null)
        commit('SET_TOKEN', null, {
            root: true
        })
        commit('SET_TOKEN', null, {
            root: true
        })
        return new Promise((resolve, reject) => {
            $axios.post('/auth/login', payload)
                .then((response) => {
                    if (response.data.status == 'success') {
                        localStorage.setItem('user_id', response.data.data.profile.user_id)
                        let tok = response.data.data.access_token
                        localStorage.setItem('token', tok)
                        commit('SET_TOKEN', tok, {
                            root: true
                        })
                    } else {
                        commit('SET_ERRORS', {
                            invalid: 'Username/Password Salah'
                        }, {
                            root: true
                        })
                    }
                    resolve(response.data)
                })
                .catch((error) => {
                    resolve(error.response)
                })
        })
    },
    doLogout({
        commit,
        state,
        getters,
        rootGetters
    }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get('/auth/logout', rootGetters.tokenHeader)
                .then((response) => {
                    if (response.status == 200) {
                        localStorage.setItem('token', null)
                        commit('SET_TOKEN', null, {
                            root: true
                        })
                    }
                    resolve(response)
                })
                .catch((error) => {
                    resolve(error.response)
                })
        })
    },
    checkResponseStatus({
        commit
    }, payload) {
        if (payload.status == 401) {
            localStorage.setItem('token', null)
            commit('SET_TOKEN', 'null', {
                root: true
            })
        } else if (payload.status == 422) {
            commit('SET_ERRORS', payload.data.errors, {
                root: true
            })
        }
    },
    getUserProfile({
        commit,
        state,
        getters,
        rootGetters
    }) {
        let profileId = localStorage.getItem('user_id')
        $axios.get(`/admin/users/${profileId}/show`, rootGetters.tokenHeader).then((response) => {
            commit('SET_PROFILE', response.data.data, {
                root: true
            })
        })
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}
