/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { CsscolorComponent } from './csscolor.component';

describe('CsscolorComponent', () => {
  let component: CsscolorComponent;
  let fixture: ComponentFixture<CsscolorComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CsscolorComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CsscolorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});