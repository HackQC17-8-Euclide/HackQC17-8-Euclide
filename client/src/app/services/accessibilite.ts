import { Stops } from './Stops'
import { Stops_times } from './Stops_times'
import { Stop } from './Stops'
import { Stop_time } from './Stops_times'
import { Pos } from './pos_bus'

export class couple<X, Y>{
    arg1: X;
    arg2: Y;
}

export class accessibilite {

    static accessibilites(pos: Pos, temps_en_s: number, temps_actuel_en_s: number): couple<Pos, number>[] {
        return accessibilite.accessibilite(pos, temps_en_s, temps_actuel_en_s, new Array<couple<Pos, number>>());
    }

    static accessibilite(pos: Pos, temps_en_s: number, temps_actuel_en_s: number, acc: couple<Pos, number>[]): couple<Pos, number>[] {
        var vitesse_pied = 10;
        var vitesse_velo = 15;
        //console.log(acc);
        //creation du tableau de résultats
        //var res = new Array<couple<Pos, number>>();
        // on ajoute le cerle de départ
        var c = new couple<Pos, number>();
        c.arg1 = pos;
        c.arg2 = accessibilite.compute_limit_distance(vitesse_pied, temps_en_s);
        acc[acc.length] = c;
        //on va chercher toutes les stations accessibles à pied 
        // on les récupère ds un tableau de couples (arrêt, distance)
        var liste_stations = new Array<couple<Stop, number>>();
        liste_stations = accessibilite.compute_accessible_stops(c.arg2, pos);

        // on le transforme en un tableau de couples (arrêt, temps d'arrivée)
        for (var i = 0; i < liste_stations.length; i++) {
            liste_stations[i].arg2 = temps_actuel_en_s + (liste_stations[i].arg2 / vitesse_pied * 3600);
        }
        //on cherche tous les trip qui passent par chaque arrêt aprèes son temps d'arrivée
        var times = new Array<Stop_time>();
        for (var i = 0; i < liste_stations.length; i++) {
            var tab = liste_stations[i].arg1.times;
            for (var j = 0; j < tab.length; j++) {
                if (tab[j].arr > liste_stations[i].arg2 && tab[j].arr < temps_actuel_en_s + temps_en_s) {
                    times[times.length] = tab[j];
                }
            }
        }
        //console.log(times);
        // console.log(times);
        //récupération de tous les stop times accessibles (sans correspondance)
        //console.log(times);
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

       // console.log(acc);
        return acc;
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
                var c = new couple<Stop, number>();
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