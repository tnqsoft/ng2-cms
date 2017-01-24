import { NgModule, ModuleWithProviders } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { HttpModule, Http, XHRBackend, RequestOptions } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';
import { MaterializeModule } from 'angular2-materialize';
import { AuthGuard, AuthService, JwtHelper } from './services';
// import { AuthenticationService, UserService } from './services';

@NgModule({
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    MaterializeModule
  ],
  exports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    RouterModule
  ],
  declarations: [],
  providers: [
    AuthGuard,
    AuthService,
    JwtHelper,
  ]
})

export class SharedModule {
  static forRoot(): ModuleWithProviders {
    return {
      ngModule: SharedModule
    };
  }
}
