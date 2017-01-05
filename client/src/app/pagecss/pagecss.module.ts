import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PagecssComponent } from './pagecss.component';
import { CsscolorComponent } from './csscolor/csscolor.component';
import { PagecssRoutes } from './pagecss.routing';
import { CssgridComponent } from './cssgrid/cssgrid.component';
import { CsshelpersComponent } from './csshelpers/csshelpers.component';

@NgModule({
  imports: [
    CommonModule,
    PagecssRoutes,
  ],
  declarations: [
    PagecssComponent,
    CsscolorComponent,
    CssgridComponent,
    CsshelpersComponent
]
})
export class PagecssModule { }