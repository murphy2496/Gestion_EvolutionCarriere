import { createRouter, createWebHistory } from 'vue-router'
import * as linkTo from '@/views/_Nav'
import { auth  } from "@/_helpers";


const routes = [
  {
    path: '/',
    name: 'Home',
    redirect: 'dashboard',
    component: linkTo.LayoutPage,
    beforeEnter:auth.authConnect,
    children:[
      { path:'dashboard', name:'Dashboard', component:linkTo.Dashboard },
      { path:'avancement', name:'Avancement', component:linkTo.Avancement },
      { path:'contractuel', name:'Contractuel', component:linkTo.Contractuel }, 
      { path:'retraite', name:'Retraite', component:linkTo.Retraite }, 
      { path:'profile', name:'Profile', component:linkTo.Profile },
      { path:'Import', name:'Import', component:linkTo.Import }, 

      {
        path: 'user', name: 'User', component: linkTo.LayoutUser,
        beforeEnter:auth.authUser,
        children:[
          { path:'list', name:'Liste RH', component:linkTo.UserList},
          { path:'add', name:'Add RH', component:linkTo.UserAdd},
          { path:'edit', name:'Edit RH', component:linkTo.UserEdit}, 
          { path:'section/:id', name:'Section RH', component:linkTo.SectionUser},   
        ]

      },
    ]
  },
  {
    path: '/login',
    name: 'Login',
    beforeEnter:auth.authDeconnect,
    component: linkTo.Login,
   
  },
  {
    path:'/:pathMatch(.*)*',
    name: 'Page404',
    component: linkTo.Page404
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})


export default router
