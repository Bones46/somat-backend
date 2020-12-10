import Vue from 'vue'
import Vuex from 'vuex'

import auth from './stores/auth.js'

import dashboard from './stores/dashboard.js'
import schools from './stores/schools.js'
import routegroups from './stores/routegroups.js'
import roles from './stores/roles.js'

Vue.use(Vuex)

const store = new Vuex.Store({

    modules: {
        auth,
        dashboard,
        schools,
        routegroups,
        roles,
    },

    state: {
        token: localStorage.getItem('token'),
        errors: [],
        profile: {},
        pageoptions: [5, 10, 25, 50, 100],
        rowpage:10,
    },

    getters: {
        isAuth: state => {
            return state.token != "null" && state.token != null
        },
        tokenHeader: state => {
            return {headers:{"Authorization":'Bearer ' + state.token}}
        }
    },

    mutations: {
        SET_TOKEN(state, payload) {
            state.token = payload
        },
        SET_ERRORS(state, payload) {
            state.errors = payload
        },
        SET_PROFILE(state, payload) {
            state.profile = payload
        },
        SET_ROWPAGE(state, payload){
            state.rowpage = payload
        },
        CLEAR_ERRORS(state) {
            state.errors = []
        }
    },

    actions: {
      setErrorState({
          commit
      }, payload) {
          let err = []
          for (var key in payload) {
            let arr = key.split(".")
            err[arr[2]] = payload[key]
          }
          commit('SET_ERRORS', err, {
              root: true
          })
      },
    }
})

export default store
