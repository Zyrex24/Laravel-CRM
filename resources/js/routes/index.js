import Dashboard from '../pages/Dashboard.vue';
import Contacts from '../pages/contacts/Index.vue';
import ContactShow from '../pages/contacts/Show.vue';
import ContactCreate from '../pages/contacts/Create.vue';
import ContactEdit from '../pages/contacts/Edit.vue';
import Leads from '../pages/leads/Index.vue';
import LeadShow from '../pages/leads/Show.vue';
import LeadCreate from '../pages/leads/Create.vue';
import LeadEdit from '../pages/leads/Edit.vue';
import Activities from '../pages/activities/Index.vue';
import ActivityShow from '../pages/activities/Show.vue';
import ActivityCreate from '../pages/activities/Create.vue';
import ActivityEdit from '../pages/activities/Edit.vue';
import Emails from '../pages/emails/Index.vue';
import EmailShow from '../pages/emails/Show.vue';
import EmailCreate from '../pages/emails/Create.vue';
import EmailTemplates from '../pages/emails/Templates.vue';
import Users from '../pages/users/Index.vue';
import UserShow from '../pages/users/Show.vue';
import UserCreate from '../pages/users/Create.vue';
import UserEdit from '../pages/users/Edit.vue';
import Settings from '../pages/Settings.vue';

const routes = [
  {
    path: '/',
    name: 'dashboard',
    component: Dashboard,
    meta: { title: 'Dashboard' }
  },
  
  // Contact routes
  {
    path: '/contacts',
    name: 'contacts.index',
    component: Contacts,
    meta: { title: 'Contacts' }
  },
  {
    path: '/contacts/create',
    name: 'contacts.create',
    component: ContactCreate,
    meta: { title: 'Create Contact' }
  },
  {
    path: '/contacts/:id',
    name: 'contacts.show',
    component: ContactShow,
    meta: { title: 'Contact Details' }
  },
  {
    path: '/contacts/:id/edit',
    name: 'contacts.edit',
    component: ContactEdit,
    meta: { title: 'Edit Contact' }
  },
  
  // Lead routes
  {
    path: '/leads',
    name: 'leads.index',
    component: Leads,
    meta: { title: 'Leads' }
  },
  {
    path: '/leads/create',
    name: 'leads.create',
    component: LeadCreate,
    meta: { title: 'Create Lead' }
  },
  {
    path: '/leads/:id',
    name: 'leads.show',
    component: LeadShow,
    meta: { title: 'Lead Details' }
  },
  {
    path: '/leads/:id/edit',
    name: 'leads.edit',
    component: LeadEdit,
    meta: { title: 'Edit Lead' }
  },
  
  // Activity routes
  {
    path: '/activities',
    name: 'activities.index',
    component: Activities,
    meta: { title: 'Activities' }
  },
  {
    path: '/activities/create',
    name: 'activities.create',
    component: ActivityCreate,
    meta: { title: 'Create Activity' }
  },
  {
    path: '/activities/:id',
    name: 'activities.show',
    component: ActivityShow,
    meta: { title: 'Activity Details' }
  },
  {
    path: '/activities/:id/edit',
    name: 'activities.edit',
    component: ActivityEdit,
    meta: { title: 'Edit Activity' }
  },
  
  // Email routes
  {
    path: '/emails',
    name: 'emails.index',
    component: Emails,
    meta: { title: 'Emails' }
  },
  {
    path: '/emails/create',
    name: 'emails.create',
    component: EmailCreate,
    meta: { title: 'Compose Email' }
  },
  {
    path: '/emails/:id',
    name: 'emails.show',
    component: EmailShow,
    meta: { title: 'Email Details' }
  },
  {
    path: '/emails/templates',
    name: 'emails.templates',
    component: EmailTemplates,
    meta: { title: 'Email Templates' }
  },
  
  // User routes
  {
    path: '/users',
    name: 'users.index',
    component: Users,
    meta: { title: 'Users' }
  },
  {
    path: '/users/create',
    name: 'users.create',
    component: UserCreate,
    meta: { title: 'Create User' }
  },
  {
    path: '/users/:id',
    name: 'users.show',
    component: UserShow,
    meta: { title: 'User Details' }
  },
  {
    path: '/users/:id/edit',
    name: 'users.edit',
    component: UserEdit,
    meta: { title: 'Edit User' }
  },
  
  // Settings
  {
    path: '/settings',
    name: 'settings',
    component: Settings,
    meta: { title: 'Settings' }
  }
];

export default routes;
