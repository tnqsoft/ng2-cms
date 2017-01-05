import { Routes, RouterModule } from '@angular/router';
import { PagecssComponent } from './pagecss.component';
import { CsscolorComponent } from './csscolor/csscolor.component';
import { CssgridComponent } from './cssgrid/cssgrid.component';
import { CsshelpersComponent } from './csshelpers/csshelpers.component';

const routes: Routes = [
  {
    path: 'css',
    component: PagecssComponent,
    children: [
      { path: 'color', component: CsscolorComponent },
      { path: 'grid', component: CssgridComponent },
      { path: 'helpers', component: CsshelpersComponent },
    ]
  }
];

export const PagecssRoutes = RouterModule.forChild(routes);