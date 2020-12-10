import IndexAdmin from './components/index.vue'
import DashboardPage from './pages/home.vue'
import LoginPage from './pages/login.vue'

import RegisterSchoolPage from './pages/register/schools/index.vue'
import ProfileSchoolPage from './pages/register/schools/profile.vue'
import RouteGroupsPage from './pages/setting/routegroups/index.vue'
import RolesPage from './pages/setting/roles/index.vue'

const routers = [{
        path: '/',
        name: 'login',
        component: LoginPage
    },
    {
        path: '/admin',
        name: 'admin',
        meta: {
            title: '',
            requiresAuth: true
        },
        component: IndexAdmin,
        children: [{
            path: 'dashboard',
            name: 'dashboard',
            component: DashboardPage
        }, {
            path: 'register/schools',
            name: 'register_schools',
            component: RegisterSchoolPage
        }, {
            path: 'profile/school',
            name: 'profile_school',
            component: ProfileSchoolPage
        }, {
            path: 'setting/routegroups',
            name: 'setting_route_groups',
            component: RouteGroupsPage
        }, {
            path: 'setting/roles',
            name: 'setting_roles',
            component: RolesPage
        } ]
    },
];
export default routers;
