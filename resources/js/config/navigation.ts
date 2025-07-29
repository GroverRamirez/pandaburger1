import {
    LayoutGrid,
    Package,
    Plus,
    List,
    Users,
    ShoppingCart,
    CreditCard,
    Settings,
    BarChart3,
    FileText
} from 'lucide-vue-next';
import type { NavItem } from '@/types';

export const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Productos',
        href: '/productos',
        icon: Package,
    },
];

export const productNavItems: NavItem[] = [
    {
        title: 'Lista de Productos',
        href: '/productos',
        icon: List,
    },
    {
        title: 'Crear Producto',
        href: '/productos/create',
        icon: Plus,
    },
];

export const managementNavItems: NavItem[] = [
    {
        title: 'Clientes',
        href: '/clientes',
        icon: Users,
    },
    {
        title: 'Pedidos',
        href: '/pedidos',
        icon: ShoppingCart,
    },
    {
        title: 'Pagos',
        href: '/pagos',
        icon: CreditCard,
    },
];

export const reportsNavItems: NavItem[] = [
    {
        title: 'Reportes',
        href: '/reportes',
        icon: BarChart3,
    },
    {
        title: 'Documentación',
        href: '/documentacion',
        icon: FileText,
    },
];

export const configNavItems: NavItem[] = [
    {
        title: 'Configuración',
        href: '/configuracion',
        icon: Settings,
    },
];

export const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Package,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: FileText,
    },
]; 