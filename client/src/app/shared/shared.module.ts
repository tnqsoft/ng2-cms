import { NgModule, ModuleWithProviders, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { HttpModule, Http, XHRBackend, RequestOptions } from '@angular/http';
import { RouterModule, Routes, Router } from '@angular/router';
import { MaterializeModule } from 'angular2-materialize';
import { JwtHelper } from './helper';
import { AuthGuard, AuthService, UserService, AuthHttpService } from './services';
import { ValidateErrorComponent, FieldErrorComponent, FormErrorComponent } from './error';

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
    RouterModule,
    ValidateErrorComponent,
    FieldErrorComponent,
    FormErrorComponent
  ],
  declarations: [
    ValidateErrorComponent,
    FieldErrorComponent,
    FormErrorComponent
  ],
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
