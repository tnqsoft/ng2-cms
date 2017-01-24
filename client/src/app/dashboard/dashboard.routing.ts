import { Routes, RouterModule } from '@angular/router';
import { DashboardComponent } from './dashboard.component';
import { AuthGuard } from '../shared/services';

const routes: Routes = [
  {
    path: 'dashboard',
    component: DashboardComponent,
    children: [
      // { path: 'color', component: CsscolorComponent },
      // { path: 'grid', component: CssgridComponent },
      // { path: 'helpers', component: CsshelpersComponent },
    ]
  }
];

export const DashboardRoutes = RouterModule.forChild(routes);
