/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { CsshelpersComponent } from './csshelpers.component';

describe('CsshelpersComponent', () => {
  let component: CsshelpersComponent;
  let fixture: ComponentFixture<CsshelpersComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CsshelpersComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CsshelpersComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});