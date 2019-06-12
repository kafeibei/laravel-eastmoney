const getComponent = page => () => import(`../components/${page}`)

export default [
  {
    path: '',
    redirect: '/form'
  },
  {
    path: '/page',
    component: getComponent('View'),
    children: [
      {
        path: '/form',
        component: getComponent('Form')
      },
      {
        path: '/east',
        component: getComponent('East')
      },
      {
        path: '/search',
        component: getComponent('Search')
      }
    ]
  }
];
