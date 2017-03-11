import { Component, ChangeDetectionStrategy, ChangeDetectorRef, OnInit } from '@angular/core';
import { Observable } from 'rxjs/Rx';



const DIGIT_LIMIT = 10;

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  changeDetection: ChangeDetectionStrategy.OnPush
})

export class AppComponent implements OnInit {
  private timer: Observable<any>;
  private time: Date;
  private hours: string;
  private minutes: string;
  private seconds: string;
  constructor(private changeDetector: ChangeDetectorRef) {
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
}
