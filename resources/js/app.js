import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';
import LandingPage from './components/LandingPage.vue';
import SubmissionForm from './components/SubmissionForm.vue';
import AdminLogin from './components/admin/AdminLogin.vue';
import AdminLayout from './components/admin/AdminLayout.vue';
import AdminDashboard from './components/admin/AdminDashboard.vue';
import AdminContributions from './components/admin/AdminContributions.vue';
import AdminSettings from './components/admin/AdminSettings.vue';
import AdminParticipants from './components/admin/AdminParticipants.vue';

const routes = [
    { path: '/', component: LandingPage },
    { path: '/soumettre', component: SubmissionForm },
    { path: '/admin/login', component: AdminLogin, meta: { guest: true } },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', redirect: '/admin/dashboard' },
            { path: 'dashboard', component: AdminDashboard },
            { path: 'contributions', component: AdminContributions },
            { path: 'participants', component: AdminParticipants },
            { path: 'settings', component: AdminSettings },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() { return { top: 0 }; },
});

// Navigation guard
router.beforeEach(async (to) => {
    if (to.meta.requiresAuth || to.meta.guest) {
        try {
            const res = await fetch('/admin/me', { headers: { 'Accept': 'application/json' } });
            const loggedIn = res.ok;
            if (to.meta.requiresAuth && !loggedIn) return '/admin/login';
            if (to.meta.guest && loggedIn) return '/admin/dashboard';
        } catch {
            if (to.meta.requiresAuth) return '/admin/login';
        }
    }
});

createApp(App).use(router).mount('#app');
