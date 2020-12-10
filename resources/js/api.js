import axios from 'axios'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

const $axios = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json'
    }
});

$axios.interceptors.request.use(config => {
    NProgress.start()
    return config
})

$axios.interceptors.response.use(response => {
    NProgress.done()
    return response
}, function(error) {
    NProgress.done()
    return error.response
})

export default $axios;
