import { Component } from '@angular/core';
import {pos_bus} from './services/pos_bus';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'app works hihi :) !';
  pos_bus = new pos_bus();
  Hashset = { 'pierre': 10 };
  assign() {
    this.Hashset[2] = 3;
  }
  constructor(){
    this.assign();
    this.pos_bus.get_pos_bus(1);
  }
}
