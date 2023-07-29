import {createWebHistory, createRouter} from "vue-router";
//homepage

//auth route
import HomePage from "../components/HomePage.vue";
import Pagination from "../components/partials/Pagination.vue";

const routes = [
    {
        path:"/report",
        name:"HomePage",
        component:HomePage,
    },

    {
        path:"/paginate",
        name:"Pagination",
        component:Pagination,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'exact-active-link',
});

// user already login
router.beforeEach((to, from, next) => {
    
});
export default router;
