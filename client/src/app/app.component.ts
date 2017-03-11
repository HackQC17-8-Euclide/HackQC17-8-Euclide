import { Component, ChangeDetectionStrategy, ChangeDetectorRef, OnInit, AfterContentInit } from '@angular/core';
import { Observable } from 'rxjs/Rx';
import { MapService } from './services/map.service';

const DIGIT_LIMIT = 10;

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [MapService],
  changeDetection: ChangeDetectionStrategy.OnPush
})



export class AppComponent implements OnInit,AfterContentInit {
  static currentPos: any;
  private map: any;
  private timer: Observable<any>;
  private time: Date;
  private hours: string;
  private minutes: string;
  private seconds: string;
  private options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
  };

  constructor(private mapService: MapService, private changeDetector: ChangeDetectorRef) {
    this.timer = Observable.timer(1000, 1000);

  }


  ngOnInit() {

    this.timer.subscribe(
      //onNext
      () => {

        //operations
        this.tick();
        this.formatTimer();
        this.changeDetector.markForCheck();

      }
      ,
      //onError
      null
      ,
      //onComplete
      () => { this.changeDetector.markForCheck(); });

  }

  ngAfterContentInit() {
    this.initLocation();
    this.map = L.map("mapId", {
      zoomControl: false,
      center: L.latLng(45.404476, -71.888351),
      zoom: 12,
      minZoom: 5,
      maxZoom: 20
    });
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      maxZoom: 18,
      id: 'digitalglobe.nako6329',
      accessToken: 'pk.eyJ1IjoiZWxjYXJpc21hIiwiYSI6ImNqMDVtY2U0ZzBtczAzMnFycThhdTJncXQifQ.T8Yr0w4eBuccD_2q7KbMGQ'
    }).addTo(this.map);
  }
  tick() {
    this.time = new Date();
  }

  formatTimer() {

    if (this.time.getHours() < DIGIT_LIMIT) {
      this.hours = '0' + String(this.time.getHours());
    }
    else {
      this.hours = String(this.time.getHours());
    }

    if (this.time.getMinutes() < DIGIT_LIMIT) {
      this.minutes = '0' + String(this.time.getMinutes());
    }
    else {
      this.minutes = String(this.time.getMinutes());
    }

    if (this.time.getSeconds() < DIGIT_LIMIT) {
      this.seconds = '0' + String(this.time.getSeconds());
    }
    else {
      this.seconds = String(this.time.getSeconds());
    }
  }

  updateOutput(event: any, output: any) {
    output.value = String(event.target.value) + ' min';
  }

  initLocation() {
    navigator.geolocation.getCurrentPosition(this.getPos, this.getErrorFromLocation, this.options);
  }

  getPos(pos) {
    AppComponent.currentPos = pos.coords;
  }

  getErrorFromLocation(error) {
    alert('ERROR ' + error.message);

  }
}
