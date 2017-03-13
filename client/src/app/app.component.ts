import { Component, ChangeDetectionStrategy, ChangeDetectorRef, OnInit, AfterContentInit } from '@angular/core';
import { Observable } from 'rxjs/Rx';
import { MapService } from './services/map.service';
import { RequestService } from './services/request.service';
import { accessibilite, couple } from './services/accessibilite'
import { Stops,Stop } from './services/Stops'
import { Stops_times } from './services/Stops_times'

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
  providers: [MapService, RequestService],
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
  private accesV: couple<Pos, number>[];
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

  constructor(private mapService: MapService, private changeDetector: ChangeDetectorRef, private reqserv: RequestService) {
    this.timer = Observable.timer(1000, 1000);
    this.timerBus = Observable.timer(2000, 2000);
  }


  ngOnInit() {
    Stops.compute_formatted_stops();
    Stops_times.compute_formatted_stop_times();

    this.reqserv.getStops()
      .then((res) => {
        Stops.stops = res;
        // console.log(Stops.stops);
        Stops.compute_formatted_stops();

      }).catch(error => {
        // console.log('plantage');
      }).then(() => {
        this.reqserv.getStopTimes()
          .then((res) => {
            Stops_times.stops_times = res;
            console.log(Stops_times.stops_times);
            Stops_times.compute_formatted_stop_times();
            this.affichageBus();
          });
      }).catch(error => {
        // console.log('plantage');
      });


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
        // console.log('cur pos', pos);

        this.initMap(pos[0], pos[1]);
        this.posi = pos;
        this.AffichageAccessibilite(parseInt(document.getElementById('fader').getAttributeNode('value').value) * 60, pos);
        this.AffichageAccessibiliteVelo(parseInt(document.getElementById('fader').getAttributeNode('value').value) * 60, pos);
        this.affichageStop();
      });


  }
  AffichageAccessibilite(tpsEnSec: number, pos: Pos) {
    this.accessi.eachLayer((layer: any) => {
      this.accessi.removeLayer(layer);
    })
    this.acces = accessibilite.accessibilites(pos, tpsEnSec, this.tempsActuel());
    // console.log(this.acces);
    for (var i of this.acces) {
      this.accessi.addLayer(L.circle([i.arg1[0], i.arg1[1]], i.arg2 * 1000, {
        stroke: false, color: 'green'
      }));

    }
    this.accessi.addTo(this.map);

  }
  AffichageAccessibiliteVelo(tpsEnSec: number, pos: Pos) {
    this.accessiVelo.eachLayer((layer: any) => {
      this.accessiVelo.removeLayer(layer);
    })
    this.accesV = accessibilite.accessibilitesV(pos, tpsEnSec, this.tempsActuel());
    for (var i of this.accesV) {
      this.accessiVelo.addLayer(L.circle([i.arg1[0], i.arg1[1]], i.arg2 * 1000, {
        stroke: false, color: 'blue'
      }));
    }
    this.accessiVelo.addTo(this.map);

  }
  tick() {
    this.time = new Date();
  }

  formatTimer() {
    var dateIso = this.time.toTimeString();
    this.hours = dateIso.substr(0, 2);
    this.minutes = dateIso.substr(3, 2);
    this.seconds = dateIso.substr(6, 2);
  }

  updateOutput(event: any, output: any) {
    output.value = String(event.target.value) + ' min';
    this.AffichageAccessibilite(parseInt(output.value) * 60, this.posi);
    this.AffichageAccessibiliteVelo(parseInt(output.value) * 60, this.posi);
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
      zoom: 13,
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


    this.reqserv.getStops()
      .then((res) => {
        Stops.stops = res;
        // console.log('Stops.stops', Stops.stops);
        Stops.compute_formatted_stops();
        this.affichageStop();
      }).catch(error => {
        // console.log('plantage 1', error);
      });

    this.reqserv.getStopTimes()
      .then((res) => {
        Stops_times.stops_times = res;
        // console.log('Stops_times.stops_times');
        Stops_times.compute_formatted_stop_times();
        // this.affichageBus();
      }).catch(error => {
        // console.log('plantage 2', error);
      });
  }
  markCurrentLocation(lat: number, long: number, radius: number = 50) {

    let marker = L.marker([lat, long]).addTo(this.map);
    var circle = L.circle([lat, long], radius,
      {
        stroke: false,
        color: 'red',
        fillColor: '#f03',
      }).addTo(this.map);
  }

  affichageBus() {
    this.positions = pos_bus.get_pos_bus(this.tempsActuel());
    //console.log(this.positions)
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
    let count = 0;
    for (var i of this.positions) {

      if (i !== undefined && count < 20) {
        this.buses.addLayer(L.marker([i.lat, i.long], { icon: myIcon }))
        count++;
      } else break;
    }
    this.buses.addTo(this.map);
  }
  compare(n1, n2) {
    // console.log(accessibilite.distance(n1.pos, this.posi) - accessibilite.distance(n2.pos, this.posi));
    // console.log(accessibilite.distance(n1.pos, n1.pos));
    return accessibilite.distance(n1.pos, this.posi) - accessibilite.distance(n2.pos, this.posi);
  }
  affichageStop() {
    var posit = Stops.formatted_stops;
    let myIcon = L.icon({
      iconUrl: '../icon_bus.png',
      iconSize: [10, 10],
      iconAnchor: [22, 94],
      popupAnchor: [-3, -76],
      shadowSize: [68, 95],
      shadowAnchor: [22, 94]
    })
    let count = 0;
    console.log('posi',this.posi);
    
    var sorrtedArray: Array<Stop> = posit.sort((n1, n2) => -1*this.compare(n1, n2));

    // console.log(this.map, 'this.map');
    for (var i of sorrtedArray) {
      if (i !== undefined && count < 500) {
        this.map.addLayer(L.marker([i.lat, i.long], { icon: myIcon }))
        count++;
      } else break;
    }
  }
  projectPoint(x, y) {
    let point = this.map.latLngToLayerPoint(new L.LatLng(y, x));
    this.stream.point(point.x, point.y);
  }

  projectPoint2(lng, lat) {
    return this.map.latLngToLayerPoint(new L.LatLng(lat, lng));
  }

}

