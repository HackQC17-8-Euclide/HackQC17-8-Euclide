import {Stop_time} from'./Stops_times'

export class Stop{
  id:number;
  lat:number;
  long:number;
  times:Stop_time[]= new Array <Stop_time>();

  constructor(id:number,lat:number,long:number){
    this.id=id;
    this.lat=lat;
    this.long=long;
  }

}


export class Stops{
  static formatted_stops:Stop[] = new Array<Stop>();
  static stops=[
  {
    "_id": 1,
    "lat": 82.704318,
    "long": 32.656626
  },
  {
    "_id": 2,
    "lat": -83.993829,
    "long": 42.122138
  },
  {
    "_id": 3,
    "lat": 80.156553,
    "long": -55.482735
  },
  {
    "_id":4,
    "lat": 10.262653,
    "long": -23.911256
  },
  {
    "_id": 5,
    "lat": -84.136272,
    "long": -119.920749
  },
  {
    "_id": 6,
    "lat": 10.072686,
    "long": -147.72684
  },
  {
    "_id": 7,
    "lat": -39.892698,
    "long": -21.803235
  },
  {
    "_id": 8,
    "lat": 70.833773,
    "long": 18.279918
  },
  {
    "_id": 9,
    "lat": 25.223348,
    "long": -15.008411
  },
  {
    "_id": 10,
    "lat": 66.275585,
    "long": -120.502812
  }
];



static compute_formatted_stops(){
    for (var i=0;i<Stops.stops.length;i++){
      Stops.formatted_stops[i]=new Stop(Stops.stops[i]._id,Stops.stops[i].lat,Stops.stops[i].long);
    }
  }
}