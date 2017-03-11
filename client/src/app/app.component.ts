import { Component } from '@angular/core';
import {pos_bus} from './services/pos_bus';
import {Pos} from './services/pos_bus';
import {accessibilite} from './services/accessibilite'
import {couple} from './services/accessibilite'

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'app works hihi :) !';
  pos_bus = new pos_bus();
  Hashset = { 'pierre': 10 };
  acces = new Array<couple<Pos,number>>();
  pos = new Pos();
  assign() {
    this.Hashset[2] = 3;
  }
  constructor(){
    this.assign();
    this.pos_bus.get_pos_bus(5050);
    this.pos.lat=23.5222;
    this.pos.long= 0;
    this.acces = accessibilite.accessibilites(this.pos,14400,0);
   
  }
}
