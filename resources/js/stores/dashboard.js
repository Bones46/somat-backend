import $axios from '../api.js'

const state = () => ({
    test: {
        student: {
            profile: {
                'name': 'Renan',
                'ttl': '1990-12-08'
            },
            student: {
                'nisn': '123123',
                'nis': '123'
            },
            location: {
                'address': 'Jl tampa batas'
            }
        },
        parent: [{
                profile: {
                    'name': 'Ayah',
                    'email': 'test@mail.com',
                    'ttl': '1990-12-08'
                },
                location: {
                    'address': 'Jl tampa batas'
                }
            },
            {
                profile: {
                    'name': 'Ibu',
                    'ttl': '1990-12-08',
                    'email': 'test@mail.com',
                },
                location: {
                    'address': 'Jl tampa batas'
                }
            }
        ]
    },
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.test = payload
    },
}

const actions = {
    submitTest({
        dispatch,
        commit,
        state,getters, rootGetters
    }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/admin/dashboard`, state.test,rootGetters.tokenHeader)
                .then((response) => {
                    resolve(response.data)
                })
                .catch((error) => {
                    if (error.response.status == 422) {
                        commit('SET_ERRORS', error.response.data.errors, {
                            root: true
                        })
                    }
                })
        })
    },
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}
