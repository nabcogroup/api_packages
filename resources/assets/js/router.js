import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const router = new VueRouter(
    {
        routes: [
            {
                name: 'property',
                path: '/property',
                component: require('./components/views/properties/List')
            },
            {
                name: 'property.create',
                path: '/property/create',
                component: require('./components/views/properties/Create')
            },
            {
                name: 'fixed-asset',
                path: '/fixed-asset',
                component: require('./components/views/fixedassets/List')
            },
            {
                name: 'fixed-asset.view',
                path: '/fixed-asset/view/:id',
                component: require('./components/views/fixedassets/View')
            },

        ]
        
    }
)

export default router;


