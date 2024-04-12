import { createRouter, createWebHistory } from 'vue-router';
import AppLayout from '@/layout/AppLayout.vue';
import WebsiteLayout from '@/layout/WebsiteLayout.vue';
const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/admin',
            component: AppLayout,
            children: [
                {
                    path: 'dashboard',
                    name: 'dashboard',
                    component: () => import('@/views/Dashboard.vue')
                },

                {
                    path: 'session/:id', // Define a dynamic segment ':id'
                    name: 'session',
                    component: () => import('@/views/pages/admin/Session.vue'),
                    props: true // Enable passing route params as props
                },
                
                {
                    path: 'orders', 
                    name: 'orders',
                    component: () => import('@/views/pages/admin/Orders.vue')
                },
                {
                    path: 'customers',
                    name: 'customers',
                    component: () => import('@/views/pages/admin/Customers.vue')
                },

                {
                    path: 'menu',
                    name: 'menu',
                    component: () => import('@/views/pages/admin/Menu.vue')
                },
                {
                    path: 'counter',
                    name: 'counter',
                    component: () => import('@/views/pages/admin/Counter.vue')
                },
                {
                    path: 'suppliers',
                    name: 'suppliers',
                    component: () => import('@/views/pages/admin/Suppliers.vue')
                },
                {
                    path: 'food-stocks',
                    name: 'food-stocks',
                    component: () => import('@/views/pages/admin/FoodStocks.vue')
                },
                {
                    path: 'resort-items',
                    name: 'resort-items',
                    component: () => import('@/views/pages/admin/ResortItems.vue')
                },
                {
                    path: 'rooms',
                    name: 'rooms',
                    component: () => import('@/views/pages/admin/Rooms.vue')
                },
                {
                    path: 'guests',
                    name: 'guests',
                    component: () => import('@/views/pages/admin/Guests.vue')
                },
                {
                    path: 'reservations',
                    name: 'reservations',
                    component: () => import('@/views/pages/admin/Reservations.vue')
                },
                {
                    path: 'accounts',
                    name: 'accounts',
                    component: () => import('@/views/pages/admin/Accounts.vue')
                },
                {
                    path: '/uikit/floatlabel',
                    name: 'floatlabel',
                    component: () => import('@/views/uikit/FloatLabel.vue')
                },
                {
                    path: '/uikit/invalidstate',
                    name: 'invalidstate',
                    component: () => import('@/views/uikit/InvalidState.vue')
                },
                {
                    path: '/uikit/button',
                    name: 'button',
                    component: () => import('@/views/uikit/Button.vue')
                },
                {
                    path: '/uikit/table',
                    name: 'table',
                    component: () => import('@/views/uikit/Table.vue')
                },
                {
                    path: '/uikit/list',
                    name: 'list',
                    component: () => import('@/views/uikit/List.vue')
                },
                {
                    path: '/uikit/tree',
                    name: 'tree',
                    component: () => import('@/views/uikit/Tree.vue')
                },
                {
                    path: '/uikit/panel',
                    name: 'panel',
                    component: () => import('@/views/uikit/Panels.vue')
                },

                {
                    path: '/uikit/overlay',
                    name: 'overlay',
                    component: () => import('@/views/uikit/Overlay.vue')
                },
                {
                    path: '/uikit/media',
                    name: 'media',
                    component: () => import('@/views/uikit/Media.vue')
                },
                {
                    path: '/uikit/menu',
                    component: () => import('@/views/uikit/Menu.vue'),
                    children: [
                        {
                            path: '/uikit/menu',
                            component: () => import('@/views/uikit/menu/PersonalDemo.vue')
                        },
                        {
                            path: '/uikit/menu/seat',
                            component: () => import('@/views/uikit/menu/SeatDemo.vue')
                        },
                        {
                            path: '/uikit/menu/payment',
                            component: () => import('@/views/uikit/menu/PaymentDemo.vue')
                        },
                        {
                            path: '/uikit/menu/confirmation',
                            component: () => import('@/views/uikit/menu/ConfirmationDemo.vue')
                        }
                    ]
                },
                {
                    path: '/uikit/message',
                    name: 'message',
                    component: () => import('@/views/uikit/Messages.vue')
                },
                {
                    path: '/uikit/file',
                    name: 'file',
                    component: () => import('@/views/uikit/File.vue')
                },
                {
                    path: '/uikit/charts',
                    name: 'charts',
                    component: () => import('@/views/uikit/Chart.vue')
                },
                {
                    path: '/uikit/misc',
                    name: 'misc',
                    component: () => import('@/views/uikit/Misc.vue')
                },
                {
                    path: '/blocks',
                    name: 'blocks',
                    component: () => import('@/views/utilities/Blocks.vue')
                },
                {
                    path: '/utilities/icons',
                    name: 'icons',
                    component: () => import('@/views/utilities/Icons.vue')
                },
                {
                    path: 'pages/timeline',
                    name: 'timeline',
                    component: () => import('@/views/pages/Timeline.vue')
                },
                {
                    path: '/pages/empty',
                    name: 'empty',
                    component: () => import('@/views/pages/Empty.vue')
                },
                {
                    path: '/pages/crud',
                    name: 'crud',
                    component: () => import('@/views/pages/Crud.vue')
                },
                {
                    path: '/documentation',
                    name: 'documentation',
                    component: () => import('@/views/utilities/Documentation.vue')
                }
            ]
        },
        {
            path: '/website',
            component: WebsiteLayout,
            children: [
                {
                    path: 'landing',
                    name: 'landing',
                    component: () => import('@/views/pages/website/Landing.vue')
                }
            ]
        },
        {
            path: '/pages/notfound',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        },

        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        // {
        //     path: '/auth/access',
        //     name: 'accessDenied',
        //     component: () => import('@/views/pages/auth/Access.vue')
        // },
        {
            path: '/test',
            name: 'test',
            component: () => import('@/views/Test.vue')
        }
    ]
});

export default router;
