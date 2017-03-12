import { Component, ChangeDetectionStrategy, ChangeDetectorRef, OnInit, AfterContentInit } from '@angular/core';
import { Observable } from 'rxjs/Rx';
import { MapService } from './services/map.service';
import * as d3 from 'd3';

const DIGIT_LIMIT = 10;

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
  private time: Date;
  private hours: string;
  private minutes: string;
  private seconds: string;
  private stream: any;
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
    this.initLocation()
      .catch(() => {
        this.initMap(45.404476, -71.888351);
      })
      .then((pos: any) => {
        this.initMap(pos[0], pos[1]);
      });

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
      zoom: 12,
      minZoom: 5,
      maxZoom: 20
    });
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution:
      'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors,' +
      '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      maxZoom: 18,
      id: 'digitalglobe.nako6329',
      accessToken: 'pk.eyJ1IjoiZWxjYXJpc21hIiwiYSI6ImNqMDVtY2U0ZzBtczAzMnFycThhdTJncXQifQ.T8Yr0w4eBuccD_2q7KbMGQ'
    }).addTo(this.map);



    // var circleBigPointsData, arretsColorScale, arretsReseau;
    // var position = [], points = [];

    // let bigPoints = [
    //   { id: 1, lat: 45.5293129, lng: -73.6019931, x: 300674, y: 5042688, r: 5, name: "Bixi Montréal", color: "#F10B0B" }
    // ];
    // // console.log("bigPoints", bigPoints);
    // let circleBigPointsData = g_circleBigPoints.selectAll("circle.bigPoints").data(bigPoints)
    // circleBigPointsData.enter().append("circle")
    // circleBigPointsData.exit().remove()
    // circleBigPointsData
    //   .attr("class", "bigPoints")
    //   .on("click", function (d, i) { console.log("Arret clique:", d); })
    //   .attr("cx", function (d) { return d.x; })
    //   .attr("cy", function (d) { return d.y; })
    //   .attr("r", function (d) { return d.r; })
    //   .attr("stroke", "#ccc")
    //   .attr("stroke-width", 0.2)
    //   .attr("fill", function (d) { return d.color; });
    this.markCurrentLocation(lat, long);
  }
  markCurrentLocation(lat: number, long: number) {

    // let svg = d3.select(this.map.getPanes().overlayPane).append("svg"),
    //   g = svg.append("g").attr("class", "leaflet-zoom-hide");
    // let g_position = g.append('g').attr('class', 'g_position'),
    //   g_stops = g.append('g').attr('class', 'g_stops');
    // let projection = d3.geoMercator();
    // let aa = [45.404476, -71.888351];
    // //let bb = [-122.389809, 37.72728];
    // svg.selectAll("circle")
    //   .data([aa]).enter()
    //   .append("circle")
    //   .attr("cx", (d: any) => { console.log(projection(d)); return projection(d)[0]; })
    //   .attr("cy", (d: any) => { return projection(d)[1]; })
    //   .attr("r", "15")
    //   .attr("fill", "green")
    let marker = L.marker([lat, long]).addTo(this.map);
    var circle = L.circle([lat, long],100,
    {
    color: 'red',
    fillColor: '#f03',
    //fillOpacity: 0.5,
  }).addTo(this.map);

  }

  projectPoint(x, y) {
    let point = this.map.latLngToLayerPoint(new L.LatLng(y, x));
    this.stream.point(point.x, point.y);
  }

  projectPoint2(lng, lat) {
    return this.map.latLngToLayerPoint(new L.LatLng(lat, lng));
  }

}
