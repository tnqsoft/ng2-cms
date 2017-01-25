import { NgModule, ModuleWithProviders, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpModule, Http, XHRBackend, RequestOptions } from '@angular/http';
import { RouterModule, Routes, Router } from '@angular/router';
import { MaterializeModule } from 'angular2-materialize';
import { AuthGuard, AuthService, JwtHelper, UserService, AuthHttpService } from './services';

@NgModule({
  imports: [
    BrowserModule,
    FormsModule,
    ReactiveFormsModule,
    HttpModule,
    MaterializeModule
  ],
  exports: [
    BrowserModule,
    FormsModule,
    ReactiveFormsModule,
    HttpModule,
    RouterModule
  ],
  declarations: [],
  providers: [
    AuthGuard,
    AuthService,
    JwtHelper,
    UserService,
    {
      provide: AuthHttpService,
      useFactory: (backend: XHRBackend, options: RequestOptions, router: Router) => {
        return new AuthHttpService(backend, options, router);
      },
      deps: [XHRBackend, RequestOptions, Router]
    }
  ],
  schemas: [CUSTOM_ELEMENTS_SCHEMA]
})

export class SharedModule {
  static forRoot(): ModuleWithProviders {
    return {
      ngModule: SharedModule
    };
  }
}
