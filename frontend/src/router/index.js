import { createRouter, createWebHistory } from 'vue-router';

import Homepage from '../components/Homepage.vue';

// Admin route
import Dashboard from '../components/Dashboard.vue';
import BookList from '../components/books/BookList.vue';
import BookCreate from '../components/books/BookCreate.vue';
import BookEdit from '../components/books/BookEdit.vue';
import BookDetails from '../components/books/BookDetails.vue';
import MemberList from '../components/members/MemberList.vue';
import LoanList from '../components/loans/LoanList.vue';
import LoanReport from '../components/reports/LoanReport.vue';
import Login from '../components/Login.vue';
import CategoryList from '../components/categories/CategoryList.vue';
import CategoryCreate from '../components/categories/CategoryCreate.vue';
import CategoryEdit from '../components/categories/CategoryEdit.vue';

// User route
import BookListMember from '../components/members/BookList.vue';
import BookDetailsMember from '../components/members/BookDetails.vue';
import LoanHistory from '../components/members/LoanHistory.vue';
import MemberLogin from '../components/members/Login.vue';
import MemberRegister from '../components/members/MemberRegister.vue';
import MemberProfile from '../components/members/MemberProfile.vue';


// import api from '../services/api';

const routes = [
  {
    path: '/',
    name: 'Homepage',
    component: Homepage,
  },
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresAuth: false },
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/books',
    name: 'BookList',
    component: BookList,
    meta: { requiresAuth: true },
  },
  {
    path: '/books/create',
    name: 'BookCreate',
    component: BookCreate,
    meta: { requiresAuth: true },
  },
  {
    path: '/books/:id/edit',
    name: 'BookEdit',
    component: BookEdit,
    meta: { requiresAuth: true },
  },
  {
    path: '/books/:id',
    name: 'BookDetails',
    component: BookDetails,
    meta: { requiresAuth: true },
  },
  {
    path: '/members',
    name: 'MemberList',
    component: MemberList,
    meta: { requiresAuth: true },
  },
  {
    path: '/loans',
    name: 'LoanList',
    component: LoanList,
    meta: { requiresAuth: true },
  },
  {
    path: '/reports/loans',
    name: 'LoanReport',
    component: LoanReport,
    meta: { requiresAuth: true },
  },
  {
    path: '/member',
    redirect: '/member/books',
  },
  {
    path: '/member/login',
    name: 'MemberLogin',
    component: MemberLogin,
    meta: { requiresAuth: false, isMember: true }
  },
  {
    path: '/member/books',
    name: 'BookListMember',
    component: BookListMember,
    meta: { requiresAuth: true, isMember: true },
  },
  {
    path: '/member/books/:id',
    name: 'BookDetailsMember',
    component: BookDetailsMember,
    meta: { requiresAuth: true, isMember: true },
  },
  {
    path: '/member/history',
    name: 'LoanHistory',
    component: LoanHistory,
    meta: { requiresAuth: true, isMember: true },
  },
  {
    path: '/member/register',
    name: 'MemberRegister',
    component: MemberRegister,
    meta: { requiresAuth: false, isMember: true }
  },
  {
    path: '/categories',
    name: 'CategoryList',
    component: CategoryList,
    meta: { requiresAuth: true },
  },
  {
    path: '/categories/create',
    name: 'CategoryCreate',
    component: CategoryCreate,
    meta: { requiresAuth: true },
  },
  {
    path: '/categories/:id/edit',
    name: 'CategoryEdit',
    component: CategoryEdit,
    meta: { requiresAuth: true },
  },
  {
  path: '/member/profile',
  name: 'MemberProfile',
  component: MemberProfile,
  meta: { requiresAuth: true, isMember: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('token');
  const isMemberAuthenticated = !!localStorage.getItem('memberToken');

  if (to.meta.requiresAuth && to.meta.isMember && !isMemberAuthenticated) {
    // Jika route memerlukan autentikasi member dan user belum login sebagai member
    next('/member/login');
  } else if (to.meta.requiresAuth && !to.meta.isMember && !isAuthenticated) {
    //jika route memerlukan autentikasi admin dan user belum login sebagai admin
    next('/login');
  } else if (to.path == '/member/login' && isMemberAuthenticated) {
    //jika route adalah /member/login dan user sudah login sebagai member
    next('/member/books');
  } else if (to.path == '/login' && isAuthenticated) {
    //jika route adalah /login dan user sudah login sebagai admin
    next('/dashboard');
  } else if (to.path === '/member/register' && isMemberAuthenticated) {
    // Jika route adalah /member/register dan user sudah login sebagai member
    next('/member/books');
  } else {
    next();
  }
});

export default router;