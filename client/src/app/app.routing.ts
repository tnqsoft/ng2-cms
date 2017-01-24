import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { DashboardRoutes } from './dashboard/dashboard.routing';
import { AuthGuard } from './shared/services/auth.guard';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: '**', component: DashboardComponent, canActivate: [AuthGuard] }
];

export const AppRoutes = RouterModule.forRoot(routes);
