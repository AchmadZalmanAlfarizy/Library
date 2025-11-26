// src/store.js
import { createStore } from 'vuex';
import api from './services/api';
import router from './router';

export default createStore({
  state: {
    isAdminAuthenticated: false,
    isMemberAuthenticated: false,
  },
  mutations: {
    SET_ADMIN_AUTH(state, isAuthenticated) {
      state.isAdminAuthenticated = isAuthenticated;
    },
    SET_MEMBER_AUTH(state, isAuthenticated) {
      state.isMemberAuthenticated = isAuthenticated;
    },
  },
  actions: {
    async loginAdmin({ commit }, credentials) {
       try {
         const response = await api.post('/login', credentials);
         localStorage.setItem('token', response.data.token);
         commit('SET_ADMIN_AUTH', true);
         return true; // Indicate successful login
       } catch (error) {
         console.error('Admin login failed:', error);
         throw error; // Re-throw the error for handling in the component
       }
     },
     async logoutAdmin({ commit }) {
        try {
          await api.post('/logout');
          localStorage.removeItem('token');
          commit('SET_ADMIN_AUTH', false);
          router.push('/'); // Navigate to homepage after logout
        } catch (error) {
          console.error('Admin logout failed:', error);
        }
      },
     async loginMember({ commit }, memberId) {
         try {
             const response = await api.get('/members');
             const memberData = response.data.find(member => member.member_id === memberId);
             if (memberData) {
             localStorage.setItem('memberId', memberData.id);
             localStorage.setItem('memberToken', 'dummy_member_token');
             commit('SET_MEMBER_AUTH', true);
             return true
             } else {
               throw new Error('Member ID not found!');
             }
         } catch (error) {
             console.error('Member login failed:', error);
             throw error
         }
     },
     async logoutMember({ commit }) {
        localStorage.removeItem('memberToken');
        localStorage.removeItem('memberId');
        commit('SET_MEMBER_AUTH', false);
        router.push('/'); // Navigate to homepage after logout
      },
    
  },
  getters: {
    isAdminAuthenticated: (state) => state.isAdminAuthenticated,
    isMemberAuthenticated: (state) => state.isMemberAuthenticated,
  },
});