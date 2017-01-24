import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { AppRoutes } from './app.routing';
import { LoginComponent } from './login/login.component';
import { DashboardModule } from './dashboard/dashboard.module';
import { SharedModule } from './shared/shared.module';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent
],
  imports: [
    SharedModule.forRoot(),
    AppRoutes,
    DashboardModule,
  ],
  exports: [],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
