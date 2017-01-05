/* tslint:disable:no-unused-variable */
import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { By } from '@angular/platform-browser';
import { DebugElement } from '@angular/core';

import { PagecssComponent } from './pagecss.component';

describe('PagecssComponent', () => {
  let component: PagecssComponent;
  let fixture: ComponentFixture<PagecssComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PagecssComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PagecssComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});