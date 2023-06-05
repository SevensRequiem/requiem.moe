$(document).ready(function(){
    var canvas = document.getElementById('starfield');
    var flr = Math.floor;

    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;

    var halfw = canvas.width / 2,
        halfh = canvas.height / 2,
        step = 2,
        warpZ = 12,
        speed = 0.075;
    var stampedDate = new Date();

    var ctx = canvas.getContext('2d');

    ctx.fillStyle = 'black';
    ctx.fillRect(0,0, canvas.width, canvas.height);

    function rnd(num1, num2) {
        return flr(Math.random() * num2 * 2) + num1;
    }

    function getColor() {
        return 'hsla(200,100%, ' + rnd(50,100) + '%, 1)';
    }

    var star = function() {
        var v = vec3.fromValues(rnd(0 - halfw,halfw),rnd(0 - halfh,halfh), rnd(1, warpZ));
        
        
        this.x = v[0];
        this.y = v[1];
        this.z = v[2];
        this.color = getColor();
        
        
        this.reset = function() {
            v = vec3.fromValues(rnd(0 - halfw,halfw),rnd(0 - halfh,halfh), rnd(1, warpZ));

            this.x = v[0];
            this.y = v[1];
            this.color = getColor();
            vel = this.calcVel();
        }
        
        this.calcVel = function() {
            
            return vec3.fromValues(0, 0, 0 - speed);
        };
        
        var vel = this.calcVel();
        
        this.draw = function() {
            vel = this.calcVel();
            v = vec3.add(vec3.create(), v, vel);
            var x = v[0] / v[2];
            var y = v[1] / v[2];
            var x2 = v[0] / (v[2] + speed * 0.50);
            var y2 = v[1] / (v[2] + speed * 0.50);
            
            ctx.strokeStyle = this.color;
            ctx.beginPath();
            ctx.moveTo(x, y);
            ctx.lineTo(x2, y2);
            ctx.stroke();
            
            if (x < 0 - halfw || x > halfw || y < 0 - halfh || y > halfh) {
                this.reset();
            }
        };
        
    }

    var starfield = function() {
        var numOfStars = 200;
        
        var stars = [];
        
        function _init() {
            for(var i = 0, len = numOfStars; i < len; i++) {
                stars.push(new star());
            }
        }    
        
        _init();
        
        this.draw = function() {
            ctx.translate(halfw, halfh);
            
            for(var i = 0, len = stars.length; i < len; i++) {
                var currentStar = stars[i];
                
                currentStar.draw();
            }
        };
        
    }

    var mStarField = new starfield();

    function draw() {
        
        // make 5 seconds
        var millSeconds = 1000 * 10;
        
        var currentTime = new Date();
        
        speed = 0.025;
      
        ctx.setTransform(1, 0, 0, 1, 0, 0);
        ctx.fillStyle = 'rgba(0,0,0,0.2)';
        ctx.fillRect(0,0, canvas.width, canvas.height);
        
        mStarField.draw();
        
        window.requestAnimationFrame(draw);
    }

    draw();

    window.onresize = function() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        halfw = canvas.width / 2;
        halfh = canvas.height / 2;
    };
});
/*
     FILE ARCHIVED ON 22:59:51 Nov 28, 2019 AND RETRIEVED FROM THE
     INTERNET ARCHIVE ON 19:58:30 Dec 21, 2019.
     JAVASCRIPT APPENDED BY WAYBACK MACHINE, COPYRIGHT INTERNET ARCHIVE.

     ALL OTHER CONTENT MAY ALSO BE PROTECTED BY COPYRIGHT (17 U.S.C.
     SECTION 108(a)(3)).
*/
/*
playback timings (ms):
  load_resource: 134.603
  exclusion.robots: 0.319
  CDXLines.iter: 18.22 (3)
  LoadShardBlock: 132.221 (3)
  RedisCDXSource: 16.766
  esindex: 0.021
  captures_list: 172.493
  PetaboxLoader3.resolve: 151.789 (2)
  exclusion.robots.policy: 0.3
  PetaboxLoader3.datanode: 103.128 (4)
*/