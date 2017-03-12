import { Stops } from './Stops'
import { Stops_times } from './Stops_times'
import { Stop } from './Stops'
import { Stop_time } from './Stops_times'
import { Pos } from './pos_bus'

export class couple<X, Y>{
    arg1: X;
    arg2: Y;
    constructor(x: X, y: Y) {
        this.arg1 = x;
        this.arg2 = y;
    }
}

export class accessibilite {

    static accessibilites(pos: Pos, temps_en_s: number, temps_actuel_en_s: number): couple<Pos, number>[] {
        return accessibilite.accessibilite(pos, temps_en_s, temps_actuel_en_s, new Array<couple<Pos, number>>());
    }
    static accessibilitesV(pos: Pos, temps_en_s: number, temps_actuel_en_s: number): couple<Pos, number>[] {
        return accessibilite.accessibiliteV(pos, temps_en_s, temps_actuel_en_s, new Array<couple<Pos, number>>());
    }

    static accessibilite(pos: Pos, temps_en_s: number, temps_actuel_en_s: number, acc: couple<Pos, number>[]): couple<Pos, number>[] {
        if (acc.length>20) return acc;
        var vitesse_pied = 3;
        var vitesse_velo = 9.5;
        // console.log(acc);
        //creation du tableau de résultats
        //var res = new Array<couple<Pos, number>>();
        // on ajoute le cerle de départ
        var c = new couple<Pos, number>(null, null);
        c.arg1 = pos;
        c.arg2 = accessibilite.compute_limit_distance(vitesse_pied, temps_en_s);
        var vel = new couple<Pos, number>(null, null);
        acc[acc.length] = c;
        vel.arg1 = pos;
        vel.arg2 = accessibilite.compute_limit_distance(vitesse_velo, temps_en_s);
        //acc[acc.length] = vel;
        //on va chercher toutes les stations accessibles à pied 
        // on les récupère ds un tableau de couples (arrêt, distance)
        var liste_stations = new Array<couple<Stop, number>>();
        liste_stations = accessibilite.compute_accessible_stops(c.arg2, pos);

        //on va chercher toutes les stations accessibles à velo 
        // on les récupère ds un tableau de couples (arrêt, distance)
        var liste_stations_velo = new Array<couple<Stop, number>>();
        liste_stations_velo = accessibilite.compute_accessible_stops(vel.arg2, pos);

        // on le transforme en un tableau de couples (arrêt, temps d'arrivée)
        for (var i = 0; i < liste_stations.length; i++) {
            liste_stations[i].arg2 = temps_actuel_en_s + (liste_stations[i].arg2 / vitesse_pied * 3600);
        }
          for (var i = 0; i < liste_stations_velo.length; i++) {
            liste_stations_velo[i].arg2 = temps_actuel_en_s + (liste_stations_velo[i].arg2 / vitesse_velo * 3600);
        }
        //on cherche tous les trip qui passent par chaque arrêt aprèes son temps d'arrivée
        var times = new Array<Stop_time>();
        for (var i = 0; i < liste_stations.length; i++) {
            var tab = liste_stations[i].arg1.times;
            for (var j = 0; j < tab.length; j++) {
                if (tab[j].arr > liste_stations[i].arg2 && tab[j].arr < liste_stations[i].arg2 + temps_en_s) {
                    times[times.length] = tab[j];
                }
            }
        }
           var timesV = new Array<Stop_time>();
        for (var i = 0; i < liste_stations_velo.length; i++) {
            var tab = liste_stations_velo[i].arg1.times;
            for (var j = 0; j < tab.length; j++) {
                if (tab[j].arr > liste_stations_velo[i].arg2 && tab[j].arr < liste_stations_velo[i].arg2 + temps_en_s) {
                    timesV[timesV.length] = tab[j];
                }
            }
        }
        //récupération de tous les stop times accessibles (sans correspondance)
        var acc_stop_times = new Array<Stop_time>();
        for (var i = 0; i < times.length; i++) {
            while (times[i].succ != null && times[i].succ.arr < temps_actuel_en_s + temps_en_s) {
                acc_stop_times[acc_stop_times.length] = times[i].succ;
                times[i] = times[i].succ;
            }
        }
        //ajout des sols des correspondances
        for (var stop of acc_stop_times) {
            var b = true;
            for (var s of acc) {
                if (s.arg1 == stop.stop.pos)
                    b = false;
            }
            if (b)
                var r = accessibilite.accessibilite(stop.stop.pos, temps_en_s - (stop.arr - temps_actuel_en_s), stop.arr, acc);
            //for (c of r){
            //    acc[acc.length]=c;
            //}
        }

        return acc;
    }
      static accessibiliteV(pos: Pos, temps_en_s: number, temps_actuel_en_s: number, accV: couple<Pos, number>[]): couple<Pos, number>[] {
        var vitesse_pied = 4;
        var vitesse_velo = 15;
        // console.log(acc);
        //creation du tableau de résultats
        //var res = new Array<couple<Pos, number>>();
        // on ajoute le cerle de départ
        var c = new couple<Pos, number>(null, null);
        c.arg1 = pos;
        c.arg2 = accessibilite.compute_limit_distance(vitesse_pied, temps_en_s);
        var vel = new couple<Pos, number>(null, null);
        //accV[accV.length] = c;
        vel.arg1 = pos;
        vel.arg2 = accessibilite.compute_limit_distance(vitesse_velo, temps_en_s);
        accV[accV.length] = vel;
        //on va chercher toutes les stations accessibles à pied 
        // on les récupère ds un tableau de couples (arrêt, distance)
        var liste_stations = new Array<couple<Stop, number>>();
        liste_stations = accessibilite.compute_accessible_stops(c.arg2, pos);

        //on va chercher toutes les stations accessibles à velo 
        // on les récupère ds un tableau de couples (arrêt, distance)
        var liste_stations_velo = new Array<couple<Stop, number>>();
        liste_stations_velo = accessibilite.compute_accessible_stops(vel.arg2, pos);

        // on le transforme en un tableau de couples (arrêt, temps d'arrivée)
        for (var i = 0; i < liste_stations.length; i++) {
            liste_stations[i].arg2 = temps_actuel_en_s + (liste_stations[i].arg2 / vitesse_pied * 3600);
        }
          for (var i = 0; i < liste_stations_velo.length; i++) {
            liste_stations_velo[i].arg2 = temps_actuel_en_s + (liste_stations_velo[i].arg2 / vitesse_velo * 3600);
        }
        //on cherche tous les trip qui passent par chaque arrêt aprèes son temps d'arrivée
        var times = new Array<Stop_time>();
        for (var i = 0; i < liste_stations.length; i++) {
            var tab = liste_stations[i].arg1.times;
            for (var j = 0; j < tab.length; j++) {
                if (tab[j].arr > liste_stations[i].arg2 && tab[j].arr < liste_stations[i].arg2 + temps_en_s) {
                    times[times.length] = tab[j];
                }
            }
        }
           var timesV = new Array<Stop_time>();
        for (var i = 0; i < liste_stations_velo.length; i++) {
            var tab = liste_stations_velo[i].arg1.times;
            for (var j = 0; j < tab.length; j++) {
                if (tab[j].arr > liste_stations_velo[i].arg2 && tab[j].arr < liste_stations_velo[i].arg2 + temps_en_s) {
                    timesV[timesV.length] = tab[j];
                }
            }
        }
        //récupération de tous les stop times accessibles (sans correspondance)
        var acc_stop_times = new Array<Stop_time>();
        for (var i = 0; i < times.length; i++) {
            while (times[i].succ != null && times[i].succ.arr < temps_actuel_en_s + temps_en_s) {
                acc_stop_times[acc_stop_times.length] = times[i].succ;
                times[i] = times[i].succ;
            }
        }
        //ajout des sols des correspondances
        for (var stop of acc_stop_times) {
            var b = true;
            for (var s of accV) {
                if (s.arg1 == stop.stop.pos)
                    b = false;
            }
            if (b)
                var r = accessibilite.accessibilite(stop.stop.pos, temps_en_s - (stop.arr - temps_actuel_en_s), stop.arr, accV);
            //for (c of r){
            //    acc[acc.length]=c;
            //}
        }

        return accV;
    }

    static compute_limit_distance(vit_km_h: number, time_sec: number): number {
        //renvoie la distance en km parcourrue en un temps t
        return vit_km_h * time_sec / 3600;
    }

    static compute_accessible_stops(limit_dist: number, ma_pos: Pos): couple<Stop, number>[] {
        if (limit_dist < 0) return null;
        //calcule pour chaque arrêt s'il est atteingnable à pied et sa distance à l'utilisateur
        var tab = Stops.formatted_stops;
        var stop_dist = new Array<couple<Stop, number>>();
        var accessible_stop = new Array<boolean>();
        for (var i = 0; i < tab.length; i++) {
            var d = this.distance(tab[i].pos, ma_pos);
            if (d < limit_dist) {
                var c = new couple<Stop, number>(null, null);
                c.arg1 = tab[i];
                c.arg2 = d;
                stop_dist[i] = c;
            }
        }
        return stop_dist;
    }

    static distance(pos: Pos, ma_pos: Pos): number {
        //calcule la distance entre une stations et ma position
        //retourne le résultat en km
        var R = 6378;
        var x = (pos.lat) * R * Math.PI / 180;
        var y = (pos.long) * R * Math.PI * Math.cos(ma_pos.lat * Math.PI / 180) / 180;

        var mon_x = (ma_pos.lat) * R * Math.PI / 180;
        var mon_y = (ma_pos.long) * R * Math.PI * Math.cos(ma_pos.lat * Math.PI / 180) / 180;
        return Math.sqrt(Math.pow(mon_x - x, 2) + Math.pow(mon_y - y, 2));
    }
}


export class grille {
    static compute_scores(max: Pos, min: Pos, heure_initiale: number, duree: number) {
        var N = 1000
        for (var i = 0; i < N; i++) {
            for (var j = 0; j <= i; j++) {
                var pt = grille.point(max, min, i, j);
                // charger données du point
                var s = grille.score(accessibilite.accessibilites(pt, heure_initiale, duree));
            }
        }
    }

    static point(max: Pos, min: Pos, lat: number, long: number): Pos {
        var pt = new Pos();
        pt.lat = grille.num(max.lat, min.lat, lat);
        pt.long = grille.num(max.long, min.long, long);
        return pt;
    }

    static num(max: number, min: number, l: number): number {
        var n = Math.floor(Math.log(l) / Math.LN2);
        return (min + (max - min) * (l - Math.pow(2, n)) / (Math.pow(2, n + 1)));
    }

    static score(acc: couple<Pos, number>[]): number {
        var res = 0;
        for (var c of acc) {
            res += c.arg2 * c.arg2;
        }
        return res;
    }

}