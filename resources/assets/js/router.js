import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const router = new VueRouter(
    {
        routes: [
            {
                name: 'property',
                path: '/property',
                component: require('./components/views/properties/List'),
            },
            {
                name: 'property.create',
                path: '/property/create',
                component: require('./components/views/properties/Create')
            },
        ]
        
    }
)

export default router;


