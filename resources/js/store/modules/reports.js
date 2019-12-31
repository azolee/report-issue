import axios from 'axios'
import * as types from '../mutation-types'

// state
export const state = {
    reports: [],
    report: {},
    statuses: ['new', 'processing', 'suspended', 'solved'],
    statusToButton: {
        new: 'danger',
        processing: 'warning',
        solved: 'success',
        suspended: 'secondary',
    }
}

// getters
export const getters = {
    allReports: state => state.reports,
    oneReport: state => state.report,
    allStatuses: state => state.statuses,
    getStatusToButton: state => state.statusToButton
}

// mutations
export const mutations = {

  [types.SET_REPORTS] (state, reports) {
    state.reports = reports
  },
  [types.SET_ONE_REPORT] (state, report) {
    state.report = report
  },
}

// actions
export const actions = {
  async fetchReports({ commit, state }, page, usedStatus) {
    const filterStatus =  typeof usedStatus !== 'undefined' ? '&status=' + usedStatus : '';
    const apiUrl = '/api/reports?with=report.user,report.photos' + ( page > 1 ? '&page='+page : '') + filterStatus
    const response = await axios.get(apiUrl);
    commit(types.SET_REPORTS, response.data);
  },
  async fetchOneReport({ commit }, id) {
    const apiUrl = '/api/reports/'+id+'?with=report.user,report.photos'
    const response = await axios.get(apiUrl);
    commit(types.SET_ONE_REPORT, response.data.data);
  }

  // async fetchUser ({ commit }) {
  //   try {
  //     const { data } = await axios.get('/api/user')
  //
  //     commit(types.FETCH_USER_SUCCESS, { user: data })
  //   } catch (e) {
  //     commit(types.FETCH_USER_FAILURE)
  //   }
  // },

}
