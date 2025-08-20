import { RouteRecordRaw } from 'vue-router';

export const clientRoutes: RouteRecordRaw[] = [
  {
    path: '/clientes',
    name: 'clientes.index',
    component: () => import('@/pages/Clientes/Index.vue'),
    meta: {
      title: 'Clientes',
      requiresAuth: true,
      breadcrumb: [
        { name: 'Inicio', to: '/' },
        { name: 'Clientes', to: '/clientes' }
      ]
    }
  },
  {
    path: '/clientes/crear',
    name: 'clientes.create',
    component: () => import('@/pages/Clientes/Create.vue'),
    meta: {
      title: 'Nuevo Cliente',
      requiresAuth: true,
      breadcrumb: [
        { name: 'Inicio', to: '/' },
        { name: 'Clientes', to: '/clientes' },
        { name: 'Nuevo Cliente', to: '/clientes/crear' }
      ]
    }
  },
  {
    path: '/clientes/:id',
    name: 'clientes.show',
    component: () => import('@/pages/Clientes/Show.vue'),
    props: true,
    meta: {
      title: 'Perfil del Cliente',
      requiresAuth: true,
      breadcrumb: [
        { name: 'Inicio', to: '/' },
        { name: 'Clientes', to: '/clientes' },
        { name: 'Perfil', to: '/clientes/:id' }
      ]
    }
  },
  {
    path: '/clientes/:id/editar',
    name: 'clientes.edit',
    component: () => import('@/pages/Clientes/Edit.vue'),
    props: true,
    meta: {
      title: 'Editar Cliente',
      requiresAuth: true,
      breadcrumb: [
        { name: 'Inicio', to: '/' },
        { name: 'Clientes', to: '/clientes' },
        { name: 'Perfil', to: '/clientes/:id' },
        { name: 'Editar', to: '/clientes/:id/editar' }
      ]
    }
  }
];
