import $axios from '../api.js'

const state = () => ({
    groups: [],
    routeItems: [],
    group: {
        template_id: '',
        name: '',
        description: ''
    },
    page: 1,
    totalpage: '',
    totalrow: '',
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.groups = payload
    },
    PUSH_DATA(state, payload) {
        state.groups.push(payload)
    },
    SPLICE_DATA(state, payload) {
        state.groups.splice(payload, 1)
    },
    ASSIGN_FORM(state, payload) {
        state.group = {
            template_id: payload.template_id,
            name: payload.name,
            description: payload.description
        }
        for (let i = 0; i < payload.permissions.length; i++) {
            // console.log(permissions[i])
            state.routeItems.push({
                id: payload.permissions[i].id,
                name: payload.permissions[i].name,
                slug: payload.permissions[i].slug,
                http_path: payload.permissions[i].http_path,
                menu_flag: payload.permissions[i].menu_flag,
                template_id: payload.permissions[i].template_id,
                isUpdate: false
            })
        }
    },
    ADD_ITEM_FORM(state) {
        state.routeItems.push({
            id: '',
            name: '',
            slug: '',
            http_path: '',
            menu_flag: 'N',
            template_id: state.group.template_id,
            isUpdate: true
        })
    },
    SPLICE_ITEM_FORM(state, payload) {
        state.routeItems.splice(payload, 1)
    },
    CLEAR_FORM(state) {
        state.group = {
            template_id: '',
            name: '',
            description: ''
        }
        state.routeItems = []
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
    AllPermissions: state => {
        const children = []
        const opentree = ['root']
        for (let i = 0; i < state.groups.length; i++) {
            if (state.groups[i].permissions.length) {
                children.push({
                    id: 'temp' + state.groups[i].template_id,
                    name: state.groups[i].name,
                    children: state.groups[i].permissions.map(item => ({
                        id: item.id,
                        name: item.name
                    }))
                })
                opentree.push('temp' + state.groups[i].template_id)
            }
        }

        return {
            opentree,
            tree: [{
                id: 'root',
                name: 'All Permissions',
                children
            }]
        }
    }
}

const actions = {
    getGroups({
        commit,
        state,
        rootState,
        rootGetters
    }, payload) {
        let search = payload != undefined ? payload.search : ''
        let key = payload != undefined ? payload.key : ''
        return new Promise((resolve, reject) => {
            $axios.get(`/admin/template?page=${state.page}&row=${rootState.rowpage}`, rootGetters.tokenHeader)
                .then((response) => {
                    commit('ASSIGN_DATA', response.data.data)
                    // state.totalrow = response.data.data.total
                    // state.totalpage = response.data.data.last_page
                    resolve(response.data.data)
                })
        })
    },
    detailGroups({
        commit,
        rootGetters
    }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/admin/template/${payload}`, rootGetters.tokenHeader)
                .then((response) => {
                    if (response.data.status == 'success')
                        commit('ASSIGN_FORM', response.data.data)
                    resolve(response.data)
                })
        })
    },
    submitGroups({
        dispatch,
        commit,
        state,
        getters,
        rootGetters
    }) {
        return new Promise((resolve, reject) => {
            $axios.post(`/admin/template/save`, {
                    'template': state.group,
                    'permission': state.routeItems
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
    deleteGroups({
        dispatch,
        commit,
        rootGetters
    }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/admin/template/${payload}/delete`, rootGetters.tokenHeader)
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
