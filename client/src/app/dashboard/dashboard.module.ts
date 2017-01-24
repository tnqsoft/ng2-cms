import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DashboardComponent } from './dashboard.component';
// import { DashboardRoutes } from './dashboard.routing';
import { ChangepasswordComponent } from './changepassword/changepassword.component';
import { HomeComponent } from './home/home.component';
import { DashboardRoutingModule } from './dashboard-routing.module';

@NgModule({
  imports: [
    CommonModule,
    DashboardRoutingModule
  ],
  declarations: [
    DashboardComponent,
    ChangepasswordComponent,
    HomeComponent
  ]
})
export class DashboardModule { }