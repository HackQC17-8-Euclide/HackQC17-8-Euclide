import { Stops } from './Stops'
import { Stops_times } from './Stops_times'
import { Pos } from './pos_bus'

export class accessibilité{
    ma_pos: Pos;
    stop_dist:number[];
    accessible_stop:boolean[];
    
    accessibilité(pos:Pos){
        this.ma_pos=pos;
    }

    compute_limit_distance(vit_km_h:number, time_sec:number){
        //renvoie la distance en km parcourrue en un temps t
        return vit_km_h*time_sec/3600;
    }

    compute_accessible_stops(limit_dist:number):void{
        //calcule pour chaque arrêt s'il est atteingnable à pied et sa distance à l'utilisateur
        var tab = Stops.stops;
        for (var i=0;i<tab.length;i++){
            var d = this.distance(tab[i].lat,tab[i].long);
            Stops_times[tab[i]._id]=d;
            this.accessible_stop[tab[i]._id]=d<limit_dist;
        }  
    }

    distance(lat:number, long:number):number{
        //calcule la distance entre une stations et ma position
        //retourne le résultat en km
        var R = 6378;
        var x= (lat)*R*Math.PI/180;
        var y= (long)*R*Math.PI*Math.cos(this.ma_pos.lat*Math.PI/180)/180;

        var mon_x= (this.ma_pos.lat)*R*Math.PI/180;
        var mon_y= (this.ma_pos.long)*R*Math.PI*Math.cos(this.ma_pos.lat*Math.PI/180)/180;
        return Math.sqrt(Math.pow(mon_x-x,2)+Math.pow(mon_y-y,2));
    }
}