import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/free-regular-svg-icons'

import {
  faUser, faLock, faSignOutAlt, faCog, faPlus, faInfo, faAt, faMobileAlt, faEdit, faStream
} from '@fortawesome/free-solid-svg-icons'

import {
  faGithub, faGoogle, faFacebook
} from '@fortawesome/free-brands-svg-icons'

library.add(
  faUser, faLock, faSignOutAlt, faCog, faGithub, faGoogle, faFacebook, faInfo, faPlus, faAt, faMobileAlt, faEdit, faStream
)

Vue.component('fa', FontAwesomeIcon)
