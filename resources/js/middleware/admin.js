import store from '~/store'

export default (to, from, next) => {
  if (store.getters['auth/user'].level !== 'admin') {
    next({ name: 'home' })
  } else {
    next()
  }
}
