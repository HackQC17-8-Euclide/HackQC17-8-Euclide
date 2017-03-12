import { Component, ChangeDetectionStrategy, ChangeDetectorRef, OnInit, AfterContentInit } from '@angular/core';
import { Observable } from 'rxjs/Rx';
import { MapService } from './services/map.service';
import { accessibilite, couple } from './services/accessibilite'
import { Stops } from './services/Stops'
import { pos_bus, Pos } from './services/pos_bus'
import { LayerGroup } from 'leaflet'

import * as d3 from 'd3';

const DIGIT_LIMIT = 10;
let latitu
let longit

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [MapService],
  changeDetection: ChangeDetectionStrategy.OnPush
})



export class AppComponent implements OnInit, AfterContentInit {
  private map: any;
  private timer: Observable<any>;
  private timerBus: Observable<any>;
  private time: Date;
  private hours: string;
  private minutes: string;
  private seconds: string;
  private stream: any;
  private positions: Array<Pos>;
  private posi: Pos;
  private acces: couple<Pos, number>[];
  private buses = new L.LayerGroup([]);
  private maps = new L.LayerGroup([]);
  private accessi = new L.LayerGroup([]);
  private accessiVelo = new L.LayerGroup([]);

  tempsActuel(): number {
    return parseInt(this.hours) * 3600 + parseInt(this.minutes) * 60 + parseInt(this.seconds);
  }
  private options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
  };

  constructor(private mapService: MapService, private changeDetector: ChangeDetectorRef) {
    this.timer = Observable.timer(1000, 1000);
    this.timerBus = Observable.timer(2000, 2000);
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


    this.timerBus.subscribe(
      //onNext
      () => {

        //operations
        this.affichageBus();
        /*this.formatTimer();*/
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
    this.initLocation()
      .catch(() => {
        this.initMap(45.404476, -71.888351);
      })
      .then((pos: any) => {
        this.initMap(pos[0], pos[1]);
        this.posi = pos;
        this.AffichageAccessibilite(parseInt(document.getElementById('fader').getAttributeNode('value').value) * 60, pos);
      });

  }
  AffichageAccessibilite(tpsEnSec: number, pos: Pos) {
    this.accessi.eachLayer((layer: any) => {
      this.accessi.removeLayer(layer);
    })
    this.acces = accessibilite.accessibilites(pos, tpsEnSec, this.tempsActuel());
    for (var i of this.acces) {
      this.accessi.addLayer(L.circle([i.arg1[0], i.arg1[1]], i.arg2 * 1000));
    }
    this.accessi.addTo(this.map);

  }
    AffichageAccessibiliteVelo(tpsEnSec: number, pos: Pos) {
    this.accessiVelo.eachLayer((layer: any) => {
      this.accessiVelo.removeLayer(layer);
    })
    this.acces = accessibilite.accessibilites(pos, tpsEnSec, this.tempsActuel());
    for (var i of this.acces) {
      this.accessiVelo.addLayer(L.circle([i.arg1[0], i.arg1[1]], i.arg2 * 1000,{stroke:false,color: 'red',
        fillColor: 'green'}));
    }
    this.accessiVelo.addTo(this.map);

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
    this.AffichageAccessibilite(parseInt(output.value) * 60, this.posi);
  }

  initLocation() {
    return new Promise((resolve, reject) => {
      navigator.geolocation.getCurrentPosition((position: any) => {
        resolve([position.coords.latitude, position.coords.longitude]);
      });
    });
  }

  getPos(pos): Promise<any> {

    return pos.coords;
  }

  getErrorFromLocation(error) {
    alert('ERROR ' + error.message);

  }
  initMap(lat: number, long: number) {
    this.map = L.map('mapId', {
      zoomControl: false,
      center: L.latLng(lat, long),
      zoom: 14,
      minZoom: 5,
      maxZoom: 20,
    });
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution:
      'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors,' +
      '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      maxZoom: 20,
      id: 'digitalglobe.nako6329',
      accessToken: 'pk.eyJ1IjoiZWxjYXJpc21hIiwiYSI6ImNqMDVtY2U0ZzBtczAzMnFycThhdTJncXQifQ.T8Yr0w4eBuccD_2q7KbMGQ'
    }).addTo(this.map);

    this.markCurrentLocation(lat, long);
  }
  markCurrentLocation(lat: number, long: number, radius: number = 50) {

    let marker = L.marker([lat, long]).addTo(this.map);
    var circle = L.circle([lat, long], radius,
      {
        color: 'red',
        fillColor: '#f03',
      }).addTo(this.map);
  }

  affichageBus() {
    this.positions = pos_bus.get_pos_bus(this.tempsActuel());
    for (let i of this.positions) {
      if (i !== undefined) {
        latitu = i.lat
        longit = i.long
      }
    }
    let myIcon = L.icon({
      iconUrl: '../icon_bus.png',
      iconSize: [38, 50],
      iconAnchor: [22, 94],
      popupAnchor: [-3, -76],
      shadowSize: [68, 95],
      shadowAnchor: [22, 94]
    })
    this.buses.eachLayer((layer: any) => {
      this.buses.removeLayer(layer);
    })
    for (var i of this.positions) {
      if (i !== undefined)
        this.buses.addLayer(L.marker([latitu, longit], { icon: myIcon }))
    }
    this.buses.addTo(this.map);
  }
  projectPoint(x, y) {
    let point = this.map.latLngToLayerPoint(new L.LatLng(y, x));
    this.stream.point(point.x, point.y);
  }

  projectPoint2(lng, lat) {
    return this.map.latLngToLayerPoint(new L.LatLng(lat, lng));
  }

}
